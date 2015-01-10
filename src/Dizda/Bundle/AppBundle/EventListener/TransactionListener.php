<?php

namespace Dizda\Bundle\AppBundle\EventListener;

use Dizda\Bundle\AppBundle\Event\TransactionEvent;

/**
 * Class TransactionListener
 */
class TransactionListener
{

    /**
     * @param TransactionEvent $event
     */
    public function onCreate(TransactionEvent $event)
    {
        // do something
    }

}
