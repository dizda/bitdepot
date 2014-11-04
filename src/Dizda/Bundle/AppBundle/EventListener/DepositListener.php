<?php

namespace Dizda\Bundle\AppBundle\EventListener;

use Dizda\Bundle\AppBundle\Entity\Address;
use Dizda\Bundle\AppBundle\Entity\Deposit;
use Dizda\Bundle\AppBundle\Entity\DepositTopup;
use Dizda\Bundle\AppBundle\Event\DepositEvent;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

/**
 * Class DepositListener
 */
class DepositListener
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var Deposit
     */
    private $deposit;

    /**
     * @var Address
     */
    private $address;

    /**
     * @param LoggerInterface $logger
     * @param EntityManager   $em
     */
    public function __construct(LoggerInterface $logger, EntityManager $em)
    {
        $this->logger = $logger;
        $this->em     = $em;
    }

    /**
     * @param DepositEvent $event
     */
    public function onUpdate(DepositEvent $event)
    {
        // do something
        $this->deposit = $event->getDeposit();
        $this->address = $this->deposit->getAddressExternal();

        if ($this->deposit->getType() === Deposit::TYPE_AMOUNT_EXPECTED) {

            $this->processExpectedType($event);

        } elseif ($this->deposit->getType() === Deposit::TYPE_AMOUNT_TOPUP) {

            $this->processTopupType($event);

        }
    }

    /**
     * When a deposit amount is "Expected", the amount is frozen until the deposit is fulfilled.
     * Then the amount can be withdraw.
     *
     * Because we look after the balance to fill the deposit.
     *
     * @param DepositEvent $event
     */
    private function processExpectedType(DepositEvent $event)
    {
        $this->deposit->setAmountFilled($this->address->getBalance());

        if ($this->address->getBalance() >= $this->deposit->getAmountExpected()) {
            $this->deposit->setIsFulfilled(true);

            if ($this->address->getBalance() > $this->deposit->getAmountExpected()) {
                $this->deposit->setIsOverfilled(true);
                $this->logger->warning('Address balance is higher than the expected deposit amount.', [ $this->deposit->getId() ]);
            }
        }
    }

    /**
     * For the topup type, the amount is incremental, so we can use the amount as we want.
     * And the balance of the address is not needed anymore.
     *
     * @param DepositEvent $event
     */
    private function processTopupType(DepositEvent $event)
    {
        $transactions = $event->getTransactionsInAdded();

        // Get amount of each transactions detected
        foreach ($transactions as $transaction) {

            // Add them to the continuous balance
            $this->deposit->setAmountFilled(
                bcadd($this->deposit->getAmountFilled(), $transaction->getAmount(), 8)
            );

            // And topup our deposit
            $topup = new DepositTopup();
            $topup->setTransaction($transaction);
            $topup->setDeposit($this->deposit);
            $topup->setStatus(DepositTopup::STATUS_QUEUED);

            // When $topup will be flushed, it will be automatically sent to rabbitmq through DepositTopupListener
            $this->em->persist($topup);
        }

    }
}
