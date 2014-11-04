<?php

namespace Dizda\Bundle\AppBundle\Manager;

use Dizda\Bundle\AppBundle\AppEvents;
use Dizda\Bundle\AppBundle\Entity\Address;
use Dizda\Bundle\AppBundle\Entity\AddressTransaction;
use Dizda\Bundle\AppBundle\Event\AddressTransactionEvent;
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
     * Save transactions if needed
     *
     * @param Address         $address
     * @param ArrayCollection $transactions
     */
    public function saveTransactions(Address $address, ArrayCollection $transactions)
    {
        $transactionsInAdded = [];

        // for each transactions, check if we got each in our db
        foreach ($transactions as $transaction) {
            if ($address->hasTransaction($transaction->getTxid())) {
                continue;
            }

            $this->logger->notice('Transaction added', [ $transaction->getTxid(), $address->getValue() ]);

            // scan inputs to see if our address is the emitter
            foreach ($transaction->getInputs() as $input) {
                if ($input->getAddress() !== $address->getValue()) {
                    continue;
                }

                $addressTransaction = new AddressTransaction();
                $addressTransaction->setAddress($address)
                    ->setId($transaction->getTxid())
                    ->setType(AddressTransaction::TYPE_OUT)
                    ->setAmount($input->getValue())
                    ->setAddresses([ $input->getAddress() ])
                ;

                $this->em->persist($addressTransaction);

                $this->dispatcher->dispatch(AppEvents::ADDRESS_TRANSACTION_CREATE, new AddressTransactionEvent($addressTransaction));
            }

            // if not, we scan the outputs to see if our address is the receiver
            foreach ($transaction->getOutputs() as $output) {
                if (!in_array($address->getValue(), $output->getAddresses())) {
                    continue;
                }

                $addressTransaction = new AddressTransaction();
                $addressTransaction->setAddress($address)
                    ->setId($transaction->getTxid())
                    ->setType(AddressTransaction::TYPE_IN)
                    ->setAmount($output->getValue())
                    ->setAddresses($output->getAddresses())
                ;

                $this->em->persist($addressTransaction);

                $this->dispatcher->dispatch(AppEvents::ADDRESS_TRANSACTION_CREATE, new AddressTransactionEvent($addressTransaction));

                $transactionsInAdded[] = $addressTransaction;
            }
        }

        return $transactionsInAdded;
    }

}
