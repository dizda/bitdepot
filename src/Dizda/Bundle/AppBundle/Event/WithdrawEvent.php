<?php

namespace Dizda\Bundle\AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Dizda\Bundle\AppBundle\Entity\Withdraw;

/**
 * Class WithdrawEvent
 */
class WithdrawEvent extends Event
{
    /**
     * @var Withdraw
     */
    private $withdraw;

    /**
     * @param Withdraw $withdraw
     */
    public function __construct(Withdraw $withdraw)
    {
        $this->withdraw = $withdraw;
    }

    /**
     * @return Withdraw
     */
    public function getWithdraw()
    {
        return $this->withdraw;
    }
}
