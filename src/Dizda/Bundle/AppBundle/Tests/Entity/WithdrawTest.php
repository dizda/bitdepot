<?php

namespace Dizda\Bundle\AppBundle\Tests\Entity;

use Dizda\Bundle\AppBundle\Tests\BasicUnitTest;
use Dizda\Bundle\AppBundle\Entity\Address;
use Dizda\Bundle\AppBundle\Entity\Transaction;
use Dizda\Bundle\AppBundle\Entity\Withdraw;
use Dizda\Bundle\AppBundle\Entity\WithdrawOutput;

class WithdrawTest extends BasicUnitTest
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

    /**
     * Withdraw::getWithdrawOutputsSerializable()
     */
    public function testGetWithdrawOutputsSerializable()
    {
        $withdraw = new Withdraw();
        $withdraw->addWithdrawOutput((new WithdrawOutput())
            ->setToAddress('firstAddress')
            ->setAmount('0.001')
        );
        $withdraw->addWithdrawOutput((new WithdrawOutput())
                ->setToAddress('secondAddress')
                ->setAmount('1.001')
        );

        // Send again to the firstAddress to check that do not overwrite the amount
        $withdraw->addWithdrawOutput((new WithdrawOutput())
                ->setToAddress('firstAddress')
                ->setAmount('0.001')
        );

        // Setting a change address and it's value
        $withdraw->setChangeAddress((new Address())
                ->setValue('changeAddress')
        );
        $withdraw->setChangeAddressAmount('0.01');

        $this->assertEquals([
            'firstAddress'  => '0.002',
            'secondAddress' => '1.001',
            'changeAddress' => '0.01'
        ], $withdraw->getWithdrawOutputsSerializable());
    }

    /**
     * Withdraw::setInputs()
     */
    public function testSetInputs()
    {
        $transactions = [
            (new Transaction())
                ->setAmount('0.0001'),
            (new Transaction())
                ->setAmount('0.0001'),
            (new Transaction())
                ->setAmount('0.0002'),
            (new Transaction())
                ->setAmount('0.0001'),
            (new Transaction())
                ->setAmount('0.0002'), // not used
        ];

        $withdraw = new Withdraw();
        $withdraw->setTotalOutputs('0.0004');
        $withdraw->setFees('0.0001');
        $withdraw->setInputs($transactions);

        $this->assertCount(4, $withdraw->getWithdrawInputs());
    }

//    private function isSpendableProvider()
//    {
//        return [
//            'lol' => 1
//        ];
//    }
}
