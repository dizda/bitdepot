<?php

namespace Dizda\Bundle\AppBundle\Manager;

use Dizda\Bundle\AppBundle\Entity\Address;
use Dizda\Bundle\AppBundle\Entity\AddressTransaction;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

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
     * @param EntityManager   $em
     * @param LoggerInterface $logger
     */
    public function __construct(EntityManager $em, LoggerInterface $logger)
    {
        $this->em     = $em;
        $this->logger = $logger;
    }

    /**
     * Save transactions if needed
     *
     * @param Address $address
     * @param array   $transactions
     */
    public function saveTransactions(Address $address, array $transactions)
    {
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
                    ->setType(AddressTransaction::TYPE_IN)
                    ->setAmount($input->getValue())
                    ->setAddresses([ $input->getAddress() ])
                ;

                $this->em->persist($addressTransaction);
            }

            // if not, we scan the outputs to see if our address is the receiver
            foreach ($transaction->getOutputs() as $output) {
                if (!in_array($address->getValue(), $output->getAddresses())) {
                    continue;
                }

                $addressTransaction = new AddressTransaction();
                $addressTransaction->setAddress($address)
                    ->setId($transaction->getTxid())
                    ->setType(AddressTransaction::TYPE_OUT)
                    ->setAmount($output->getValue())
                    ->setAddresses($output->getAddresses())
                ;

                $this->em->persist($addressTransaction);
            }
        }
    }

}
