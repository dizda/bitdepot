<?php

namespace Dizda\Bundle\AppBundle\Tests\Entity;

use AppBundle\Tests\BasicUnitTest;
use Dizda\Bundle\AppBundle\Entity\Address;
use Dizda\Bundle\AppBundle\Entity\Transaction;

class AddressTest extends BasicUnitTest
{

    /**
     * Address::hasTransaction()
     */
    public function testHasTransaction()
    {
        $transaction = (new Transaction())
            ->setTxid('suckMyBitecoin')
            ->setType(Transaction::TYPE_IN)
            ->setIndex(1)
        ;

        $address = new Address();
        $this->assertFalse($address->hasTransaction('suckMyBitecoin', Transaction::TYPE_IN, 1));

        $address->addTransaction($transaction);
        $this->assertTrue($address->hasTransaction('suckMyBitecoin', Transaction::TYPE_IN, 1));
        $this->assertFalse($address->hasTransaction('suckMyBitecoin', Transaction::TYPE_OUT, 1));
    }
}
