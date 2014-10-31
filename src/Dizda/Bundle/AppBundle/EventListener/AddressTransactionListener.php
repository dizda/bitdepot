<?php

namespace Dizda\Bundle\AppBundle\EventListener;

use Dizda\Bundle\AppBundle\Event\AddressTransactionEvent;

/**
 * Class AddressTransactionListener
 */
class AddressTransactionListener
{

    /**
     * @param AddressTransactionEvent $event
     */
    public function onCreate(AddressTransactionEvent $event)
    {
        // do something
    }

}
