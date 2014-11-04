<?php

namespace Dizda\Bundle\AppBundle\Event;

use Dizda\Bundle\AppBundle\Entity\Deposit;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class DepositEvent
 */
class DepositEvent extends Event
{
    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Deposit
     */
    private $deposit;

    /**
     * @var array
     */
    private $transactionsInAdded;

    /**
     * @param Deposit $deposit
     * @param array   $transactionsInAdded
     */
    public function __construct(Deposit $deposit, array $transactionsInAdded)
    {
        $this->deposit = $deposit;
        $this->transactionsInAdded = $transactionsInAdded;
    }

    /**
     * @return Deposit
     */
    public function getDeposit()
    {
        return $this->deposit;
    }

    /**
     * @return array
     */
    public function getTransactionsInAdded()
    {
        return $this->transactionsInAdded;
    }
}
