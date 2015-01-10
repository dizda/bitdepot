<?php

namespace Dizda\Bundle\AppBundle\Event;

use Dizda\Bundle\AppBundle\Entity\Transaction;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class TransactionEvent
 */
class TransactionEvent extends Event
{
    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Transaction
     */
    private $addressTransaction;

    /**
     * @param Transaction $addressTransaction
     */
    public function __construct(Transaction $addressTransaction)
    {
        $this->addressTransaction = $addressTransaction;
    }

    /**
     * @return Transaction
     */
    public function getTransaction()
    {
        return $this->addressTransaction;
    }

}
