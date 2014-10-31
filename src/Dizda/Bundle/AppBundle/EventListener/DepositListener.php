<?php

namespace Dizda\Bundle\AppBundle\EventListener;

use Dizda\Bundle\AppBundle\Event\DepositEvent;

/**
 * Class DepositListener
 */
class DepositListener
{

    /**
     * @param DepositEvent $event
     */
    public function onUpdate(DepositEvent $event)
    {
        // do something
    }

}
