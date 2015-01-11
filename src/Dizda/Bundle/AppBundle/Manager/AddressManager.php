<?php

namespace Dizda\Bundle\AppBundle\Manager;

use Dizda\Bundle\AppBundle\AppEvents;
use Dizda\Bundle\AppBundle\Entity\Address;
use Dizda\Bundle\AppBundle\Entity\Transaction;
use Dizda\Bundle\AppBundle\Entity\Application;
use Dizda\Bundle\AppBundle\Event\TransactionEvent;
use Dizda\Bundle\AppBundle\Service\AddressService;
use Dizda\Bundle\BlockchainBundle\Model\Insight\TransactionOutput;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class AddressManager
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
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

    /**
     * Generate a new address, save it to the DB.
     *
     * @param Application $application
     * @param bool $isExternal
     *
     * @return Address
     */
    public function create(Application $application, $isExternal = true)
    {
        // getting last derivation
        // internal/external?
        $lastAddress = $this->em->getRepository('DizdaAppBundle:Address')->getLastDerivation($application, $isExternal);

        if ($lastAddress !== null) {
            $derivation = $lastAddress->getDerivation() + 1; // increment the derivation
        } else {
            $derivation = 0;
        }

        $multisigAddress = $this->addressService->generateHDMultisigAddress($application, $isExternal, $derivation);

        $address = (new Address())
            ->setApplication($application)
            ->setValue($multisigAddress['address'])
            ->setRedeemScript($multisigAddress['redeemScript'])
            ->setIsExternal($isExternal)
            ->setDerivation($derivation)
            //->setScriptPubKey()
        ;

        $this->em->persist($address);

        return $address;
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
            // We only scan the outputs to see if our address is the receiver of something
            foreach ($transaction->getOutputs() as $output) {
                if (!in_array($address->getValue(), $output->getAddresses())) {
                    continue;
                }

                // Check if transaction already exist
                if ($address->hasTransaction(
                    $transaction->getTxid(),
                    Transaction::TYPE_IN,
                    $output->getIndex()
                )) {
                    continue;
                }

                $addressTransaction = new Transaction();
                $addressTransaction->setAddress($address)
                    ->setTxid($transaction->getTxid())
                    ->setType(Transaction::TYPE_IN)
                    ->setAmount($output->getValue())
                    ->setAddresses($output->getAddresses())
                    ->setIndex($output->getIndex())
                ;


                $this->logger->notice('Transaction added', [ $transaction->getTxid(), $address->getValue() ]);

                // Check is the transaction has already been spent
                $this->isTransactionSpent($transactions, $transaction, $output, $addressTransaction);

                // Then save it into DB
                $this->em->persist($addressTransaction);
                $this->dispatcher->dispatch(AppEvents::ADDRESS_TRANSACTION_CREATE, new TransactionEvent($addressTransaction));

                $transactionsInAdded[] = $addressTransaction;
            }
        }

        return $transactionsInAdded;
    }

    /**
     * Check in all transactions if the input is already spent in another transaction
     *
     * @param array               $transactions       All transactions
     * @param Transaction         $currentTransaction The current transaction reviewed
     * @param TransactionOutput   $output             Output
     * @param Transaction         $addressTransaction Transaction entity
     */
    private function isTransactionSpent($transactions, $currentTransaction, $output, $addressTransaction)
    {
        foreach ($transactions as $trans) {
            foreach ($trans->getInputs() as $in) {
                if ($currentTransaction->getTxid() === $in->getTxid() && $output->getIndex() === $in->getIndex()) {
                    $addressTransaction->markAsSpent();
                    $this->logger->notice('Transaction marked as spent', [ $currentTransaction->getTxid() ]);
                }
            }
        }
    }
}
