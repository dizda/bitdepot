<?php

namespace Dizda\Bundle\AppBundle\Tests\Command;

use Dizda\Bundle\AppBundle\Entity\AddressTransaction;
use Dizda\Bundle\AppBundle\Entity\WithdrawOutput;
use Dizda\Bundle\AppBundle\Tests\BaseFunctionalTestCommand;

/**
 * Class WithdrawCommandTest
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class WithdrawCommandTest extends BaseFunctionalTestCommand
{

    /**
     * @group functional
     */
    public function testExecuteCreateWithdraw()
    {
        $this->addTransactionAndWithdrawOutput();

        // Assert we got 2
        $this->assertCount(2, $this->em->getRepository('DizdaAppBundle:Withdraw')->findAll());

        // Run the Command!
        var_dump($this->runCommand('dizda:app:withdraw', ['-vv'], true));

        // Now we got 3 withdraws
        $this->assertCount(3, $this->em->getRepository('DizdaAppBundle:Withdraw')->findAll());

        // Select our withdraw created
        $withdraw = $this->em->getRepository('DizdaAppBundle:Withdraw')->find(3);

        // And assert that it was properly populated
        $this->assertEquals('0.00030000', $withdraw->getTotalInputs());
        $this->assertEquals('0.00020000', $withdraw->getTotalOutputs());
        $this->assertEquals('0.00010000', $withdraw->getFees());
        $this->assertEquals('0.00030000', $withdraw->getTotalOutputsWithFees());
        $this->assertEquals(
            '0100000001e5427d1972a8c1682cd4bf72ee9a2ee247f98a631fcd86b294e5ca821b59fa320000000000ffffffff01204e0000000000001976a914e5e2ad271ceea56299d2965cf8222fcb65d5bb8e88ac00000000',
            $withdraw->getRawTransaction()
        );
        $this->assertEquals('32fa591b82cae594b286cd1f638af947e22e9aee72bfd42c68c1a872197d42e5', $withdraw->getWithdrawInputs()->first()->getTxid());
        $this->assertEquals('1MxXHgScDGaA7GaJY8bGa9MsCKU6iXaiRh', $withdraw->getWithdrawOutputs()->first()->getToAddress());
        $this->assertNull($withdraw->getChangeAddress());
        $this->assertTrue($withdraw->isSpendable());
    }

    private function addTransactionAndWithdrawOutput()
    {
        $address = $this->em
            ->getRepository('DizdaAppBundle:Address')
            ->findOneByValue('3MxR1yHVpfB7cXULzpetoyNVvUeqhoaJhE')
        ;

        $transaction = (new AddressTransaction())
            ->setType(AddressTransaction::TYPE_IN)
            ->setAddress($address)
            ->setAmount('0.0003')
            ->setIndex(0)
            ->setIsSpent(false)
            ->setTxid('32fa591b82cae594b286cd1f638af947e22e9aee72bfd42c68c1a872197d42e5')
            ->setAddresses([ $address->getValue() ])
        ;

        $withdrawOutput = (new WithdrawOutput())
            ->setApplication($this->em->getRepository('DizdaAppBundle:Application')->find(1))
            ->setAmount('0.0002')
            ->setIsAccepted(true)
            ->setToAddress('1MxXHgScDGaA7GaJY8bGa9MsCKU6iXaiRh')
        ;

        $this->em->persist($transaction);
        $this->em->persist($withdrawOutput);
        $this->em->flush();
    }
}
