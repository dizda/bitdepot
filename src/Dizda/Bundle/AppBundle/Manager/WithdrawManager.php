<?php

namespace Dizda\Bundle\AppBundle\Manager;

use Dizda\Bundle\AppBundle\AppEvents;
use Dizda\Bundle\AppBundle\Entity\Application;
use Dizda\Bundle\AppBundle\Entity\Keychain;
use Dizda\Bundle\AppBundle\Entity\Withdraw;
use Dizda\Bundle\AppBundle\Event\WithdrawEvent;
use Dizda\Bundle\AppBundle\Exception\InsufficientAmountException;
use Dizda\Bundle\AppBundle\Exception\UnknownSignatureException;
use Dizda\Bundle\AppBundle\Service\BitcoreService;
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
     * @var AddressManager
     */
    private $addressManager;

    /**
     * @var BitcoreService
     */
    private $bitcoreService;

    /**
     * @param EntityManager            $em
     * @param LoggerInterface          $logger
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EntityManager $em, LoggerInterface $logger, EventDispatcherInterface $dispatcher, AddressManager $addressManager, BitcoreService $bitcoreService)
    {
        $this->em         = $em;
        $this->logger     = $logger;
        $this->dispatcher = $dispatcher;
        $this->addressManager = $addressManager;
        $this->bitcoreService = $bitcoreService;
    }

    /**
     * Search if there are outputs available to group them into a withdraw.
     *
     * @param Keychain $keychain
     *
     * @return bool|ArrayCollection
     */
    public function search(Keychain $keychain)
    {
        $outputs = $this->em->getRepository('DizdaAppBundle:WithdrawOutput')->getWhereWithdrawIsNull($keychain);

        if ($keychain->getGroupWithdrawsByQuantity() === null || count($outputs) < $keychain->getGroupWithdrawsByQuantity()) {
            return false;
        }

        return $outputs;
    }

    /**
     * Create a withdraw according to outputs
     * If sufficient money available, we can proceed to the creation of the withdraw.
     * Otherwise, the function will return null.
     *
     * @param Keychain $keychain
     * @param array    $outputs
     *
     * @return null|Withdraw
     */
    public function create(Keychain $keychain, array $outputs)
    {
        $withdraw = new Withdraw();
        $withdraw->setKeychain($keychain);
        $withdraw->setOutputs($outputs);
        // Always get a change address, but we might not use it.
        $changeAddress = $this->addressManager->create($withdraw->getWithdrawOutputs()[0]->getApplication(), false);
        $withdraw->setChangeAddress($changeAddress);

        $transactions = $this->em->getRepository('DizdaAppBundle:Transaction')
            ->getSpendableTransactions($keychain)
        ;


        foreach ($transactions as $transaction) {
            // Add input one by one to the transaction, and when it's enough we can move on and save the transaction
            $withdraw->addInput($transaction);

            if (!$withdraw->isSpendable()) {
                // if the inputs amount is not enough to cover the total output, then we add more of them
                continue;
            }

            // Let bitcore to determine if the fees are enough or not
            $bitcoreTransaction = $this->bitcoreService->buildTransaction(
                $withdraw->getWithdrawInputs(),
                $withdraw->getWithdrawOutputs(),
                $withdraw->getChangeAddress(),
                $withdraw->getWithdrawOutputs()[0]->getApplication()->getExtraFees()
            );

            $withdraw->importFromBitcore($bitcoreTransaction);

            if ($withdraw->isSpendable()) {
                // if the inputs amount is enough to cover the total output + fees, then we're good to send the transaction
                break;
            }
        }

        if (!$withdraw->isSpendable()) {
            // if the amount of inputs is insufficient, we give up the creation of the withdraw
            $this->logger->warning(
                'WithdrawManager: Insufficient amount available to create a new withdraw as requested. Available/Requested',
                [ $withdraw->getTotalInputs(), $withdraw->getTotalOutputsWithFees() ]
            );

            return null;
        }

        if (!$withdraw->getChangeAddressAmount()) {
            // Remove the change address if the amount is null, it'd avoid to monitor X unused addresses
            $withdraw->setChangeAddress(null);
            $this->em->detach($changeAddress);
        }

        $this->em->persist($withdraw);
        $this->em->flush();

        return $withdraw;
    }

    /**
     * Saving data received from Angular
     *
     * @param Withdraw $withdraw          The original $withdraw fetched from DB
     * @param array    $withdrawSubmitted The json Withdraw data submitted by angular
     *
     * @throws UnknownSignatureException
     */
    public function save(Withdraw $withdraw, $withdrawSubmitted)
    {
        // When one signer, sign the transaction
        if ($withdrawSubmitted['json_signed_transaction']) {
            $withdraw->setJsonSignedTransaction($withdrawSubmitted['json_signed_transaction']);

            // dispatch event here
        }

        // When the transaction is fully signed
        if ($withdrawSubmitted['raw_signed_transaction']) {
            $withdraw->setRawSignedTransaction($withdrawSubmitted['raw_signed_transaction']);

            // dispatch event here
        }

        // Add signature if submitted
        if ($withdrawSubmitted['signed_by']) {
            $pubKey = $this->em->getRepository('DizdaAppBundle:Identity')->findOneBy([
                'publicKey' => $withdrawSubmitted['signed_by'],
                'keychain'  => $withdraw->getKeychain()
            ]);

            // If the given PubKey doesn't exist
            if ($pubKey === null) {
                throw new UnknownSignatureException();
            }

            $withdraw->addSignature($pubKey);

            // dispatch event there, like PushOver through Rabbit ?
        }

        if ($withdrawSubmitted['is_signed'] === true) {
            $withdraw->setIsSigned(true);

            // dispatch event to sendrawtransaction !
            $this->dispatcher->dispatch(AppEvents::WITHDRAW_SEND, new WithdrawEvent($withdraw));
        }
    }
}
