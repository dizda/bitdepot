<?php

namespace Dizda\Bundle\AppBundle\Event;

use Dizda\Bundle\AppBundle\Entity\AddressTransaction;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class AddressTransactionEvent
 */
class AddressTransactionEvent extends Event
{
    /**
     * @var \Dizda\Bundle\AppBundle\Entity\AddressTransaction
     */
    private $addressTransaction;

    /**
     * @param AddressTransaction $addressTransaction
     */
    public function __construct(AddressTransaction $addressTransaction)
    {
        $this->addressTransaction = $addressTransaction;
    }

    /**
     * @return AddressTransaction
     */
    public function getAddressTransaction()
    {
        return $this->addressTransaction;
    }

}
