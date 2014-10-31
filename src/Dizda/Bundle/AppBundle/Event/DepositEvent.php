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
     * @param Deposit $deposit
     */
    public function __construct(Deposit $deposit)
    {
        $this->deposit = $deposit;
    }

    /**
     * @return Deposit
     */
    public function getDeposit()
    {
        return $this->deposit;
    }

}
