<?php

namespace Dizda\Bundle\AppBundle\Tests\Entity;

use Dizda\Bundle\AppBundle\Entity\Withdraw;
use Prophecy\PhpUnit\ProphecyTestCase;

class WithdrawTest extends ProphecyTestCase
{

    /**
     * Withdraw::isSpendable()
     * dataProvider isSpendableProvider
     */
    public function testIsSpendable()
    {
        $withdraw = new Withdraw();
        $withdraw->setTotalInputs('0.0001');
        $withdraw->setTotalOutputs('0.0002');
        $withdraw->setFees('0.0001');
        $this->assertFalse($withdraw->isSpendable());

        $withdraw->setTotalInputs('0.0002');
        $this->assertFalse($withdraw->isSpendable());

        $withdraw->setTotalInputs('0.0003');
        $this->assertTrue($withdraw->isSpendable());

        $withdraw->setTotalInputs('0.0004');
        $this->assertTrue($withdraw->isSpendable());
    }

//    private function isSpendableProvider()
//    {
//        return [
//            'lol' => 1
//        ];
//    }
}
