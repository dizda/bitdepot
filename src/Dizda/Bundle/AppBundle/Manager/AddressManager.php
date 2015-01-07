<?php

namespace Dizda\Bundle\AppBundle\Manager;

use Dizda\Bundle\AppBundle\AppEvents;
use Dizda\Bundle\AppBundle\Entity\Address;
use Dizda\Bundle\AppBundle\Entity\AddressTransaction;
use Dizda\Bundle\AppBundle\Entity\Application;
use Dizda\Bundle\AppBundle\Event\AddressTransactionEvent;
use Dizda\Bundle\AppBundle\Service\AddressService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class AddressManager
 */
class AddressManager
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
     * @var \Dizda\Bundle\AppBundle\Service\AddressService
     */
    private $addressService;

    /**
     * @param EntityManager            $em
     * @param LoggerInterface          $logger
     * @param EventDispatcherInterface $dispatcher
     * @param AddressService           $addressService
     */
    public function __construct(EntityManager $em, LoggerInterface $logger, EventDispatcherInterface $dispatcher, AddressService $addressService)
    {
        $this->em         = $em;
        $this->logger     = $logger;
        $this->dispatcher = $dispatcher;
        $this->addressService = $addressService;
    }

    public function create(Application $application, $isExternal = true)
    {
        // getting last derivation
        // internal/external?
        $derivation = $this->em->getRepository('DizdaAppBundle:Address')->getLastDerivation($application, $isExternal);

        if ($derivation === null) {
            $derivation = 0;
        }

        $this->addressService->generateHDMultisigAddress($application, $isExternal, $derivation);
    }

    /**
     * Save transactions if needed
     *
     * @param Address         $address
     * @param ArrayCollection $transactions
     *
     * @return array
     */
    public function saveTransactions(Address $address, ArrayCollection $transactions)
    {
        $transactionsInAdded = [];

        // for each transactions, check if we got each in our db
        foreach ($transactions as $transaction) {
            // scan inputs to see if our address is the emitter
            foreach ($transaction->getInputs() as $input) {
                if ($input->getAddress() !== $address->getValue()) {
                    continue;
                }

                // Check if transaction already exist
                if ($address->hasTransaction(
                    $transaction->getTxid(),
                    AddressTransaction::TYPE_OUT,
                    $input->getIndex()
                )) {
                    continue;
                }

                $addressTransaction = new AddressTransaction();
                $addressTransaction->setAddress($address)
                    ->setTxid($transaction->getTxid())
                    ->setType(AddressTransaction::TYPE_OUT)
                    ->setAmount($input->getValue())
                    ->setAddresses([ $input->getAddress() ])
                    ->setIndex($input->getIndex())
                ;

                $this->em->persist($addressTransaction);

                $this->logger->notice('Transaction added', [ $transaction->getTxid(), $address->getValue() ]);

                $this->dispatcher->dispatch(AppEvents::ADDRESS_TRANSACTION_CREATE, new AddressTransactionEvent($addressTransaction));
            }

            // if not, we scan the outputs to see if our address is the receiver
            foreach ($transaction->getOutputs() as $output) {
                if (!in_array($address->getValue(), $output->getAddresses())) {
                    continue;
                }

                // Check if transaction already exist
                if ($address->hasTransaction(
                    $transaction->getTxid(),
                    AddressTransaction::TYPE_IN,
                    $output->getIndex()
                )) {
                    continue;
                }

                $addressTransaction = new AddressTransaction();
                $addressTransaction->setAddress($address)
                    ->setTxid($transaction->getTxid())
                    ->setType(AddressTransaction::TYPE_IN)
                    ->setAmount($output->getValue())
                    ->setAddresses($output->getAddresses())
                    ->setIndex($output->getIndex())
                ;

                $this->em->persist($addressTransaction);

                $this->dispatcher->dispatch(AppEvents::ADDRESS_TRANSACTION_CREATE, new AddressTransactionEvent($addressTransaction));

                $transactionsInAdded[] = $addressTransaction;
            }
        }

        return $transactionsInAdded;
    }

}
