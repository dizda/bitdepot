<?php

namespace Dizda\Bundle\AppBundle\Manager;

use Dizda\Bundle\AppBundle\AppEvents;
use Dizda\Bundle\AppBundle\Entity\Application;
use Dizda\Bundle\AppBundle\Entity\Withdraw;
use Dizda\Bundle\AppBundle\Event\WithdrawEvent;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class WithdrawManager
 */
class WithdrawManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param EntityManager            $em
     * @param LoggerInterface          $logger
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EntityManager $em, LoggerInterface $logger, EventDispatcherInterface $dispatcher)
    {
        $this->em         = $em;
        $this->logger     = $logger;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Application $application
     *
     * @return bool|ArrayCollection
     */
    public function search(Application $application)
    {
        $outputs = $this->em->getRepository('DizdaAppBundle:WithdrawOutput')->getWhereWithdrawIsNull($application);

        if ($application->getGroupWithdrawsByQuantity() === null
            || count($outputs) < $application->getGroupWithdrawsByQuantity()) {

            return false;
        }

        return $outputs;
    }

    /**
     * @param Application $application
     * @param array       $outputs
     */
    public function create(Application $application, array $outputs)
    {
        $withdraw = new Withdraw();
        $withdraw->setKeychain($application->getKeychain());

        // Setting outputs
        foreach ($outputs as $output) {
            $withdraw->setTotalOutputs(bcadd($withdraw->getTotalOutputs(), $output->getAmount(), 8));
            $withdraw->addWithdrawOutput($output);
        }

        // TODO: Remove this line, just to test with fees!
        //$withdraw->setTotalOutputs(bcadd($withdraw->getTotalOutputs(), '0.0001', 8));

        // Setting inputs
        $transactions = $this->em->getRepository('DizdaAppBundle:AddressTransaction')
            ->getSpendableTransactions($application, $withdraw->getTotalOutputs())
        ;

        foreach ($transactions as $transaction) {
            $withdraw->setTotalInputs(bcadd($withdraw->getTotalInputs(), $transaction->getAmount(), 8));
            $withdraw->addWithdrawInput($transaction);

            // $sumOutputs >= $withdraw->getTotalOutputs()
            if (bccomp($withdraw->getTotalInputs(), $withdraw->getTotalOutputs(), 8) !== -1) {
                // if the amount collected is sufficient, we quit the foreach to do not add more inputs
                break;
            }
        }

        // TODO: Handle the case when totalInputs is equal as totalOutputs, so there is no more funds for fees
        // $sumOutputs < $withdraw->getTotalOutputs()
        if (bccomp($withdraw->getTotalInputs(), $withdraw->getTotalOutputs(), 8) === -1) {
            // if the amount of inputs is insufficient, we give up the creation of the withdraw
            $this->logger->warning(
                'WithdrawManager: Insufficient amount available to create a new withdraw as requested. Available/Requested',
                [ $withdraw->getTotalInputs(), $withdraw->getTotalOutputs() ]
            );

            return;
        }

        $this->em->persist($withdraw);

        // Create the rawtransaction
        $this->dispatcher->dispatch(AppEvents::WITHDRAW_CREATE, new WithdrawEvent($withdraw));

        $this->em->flush();
    }


    public function save(Withdraw $withdraw, $withdrawSubmitted)
    {
        if ($withdrawSubmitted['raw_signed_transaction']) {
            $withdraw->setRawSignedTransaction($withdrawSubmitted['raw_signed_transaction']);

            // dispatch event here
        }

        // Add signature if submitted
        if ($withdrawSubmitted['signed_by']) {
            $pubkey = $this->em->getRepository('DizdaAppBundle:Pubkey')->findOneBy([
                'keychain' => $withdraw->getKeychain(),
                'value'    => $withdrawSubmitted['signed_by']
            ]);

            $withdraw->addSignature($pubkey);
        }

    }
}
