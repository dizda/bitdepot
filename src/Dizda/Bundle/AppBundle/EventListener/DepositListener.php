<?php

namespace Dizda\Bundle\AppBundle\EventListener;

use Dizda\Bundle\AppBundle\Event\DepositEvent;
use Psr\Log\LoggerInterface;

/**
 * Class DepositListener
 */
class DepositListener
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param DepositEvent $event
     */
    public function onUpdate(DepositEvent $event)
    {
        // do something
        $deposit = $event->getDeposit();
        $address = $deposit->getAddressExternal();

        $deposit->setAmountFilled($address->getBalance());

        if ($address->getBalance() >= $deposit->getAmountExpected()) {
            $deposit->setIsFulfilled(true);

            if ($address->getBalance() > $deposit->getAmountExpected()) {
                $deposit->setIsOverfilled(true);
                $this->logger->warning('Address balance is higher than the expected deposit amount.', [ $deposit->getId() ]);
            }
        }
    }
}
