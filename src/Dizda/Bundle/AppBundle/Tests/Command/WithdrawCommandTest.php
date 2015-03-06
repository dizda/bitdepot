<?php

namespace Dizda\Bundle\AppBundle\Tests\Command;

use Dizda\Bundle\AppBundle\Entity\Transaction;
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

        // Mock bitcoind because of travis
//        $this->mockBitcoind();

        // Run the Command!
        $this->runCommand('dizda:app:withdraw', ['-vv'], true);
        // TODO: install bitcoind on travisci

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
        $this->assertEquals(
            '{"version":1,"inputs":[{"prevTxId":"32fa591b82cae594b286cd1f638af947e22e9aee72bfd42c68c1a872197d42e5","outputIndex":0,"sequenceNumber":4294967295,"script":"","output":{"satoshis":30000,"script":"OP_HASH160 20 0x7a963a9997572c5f05efcb84bde9c1e8bcb809c3 OP_EQUAL"},"threshold":2,"publicKeys":["02087a30059abeb82ceb8b0a0413c16307a0d29cec97073bbc8d4a584e60f19f23","029bfbac8f2bfca762df3ecd1500bdf291a9dad7c7491533a8b6d8925c9039432f","02a3ce2f9b90ac59d6cd5a2a01b3c1d5e9e379627ae9c9e1b2a3542f8cf80f7ae7"],"signatures":[null,null,null]}],"outputs":[{"satoshis":20000,"script":"OP_DUP OP_HASH160 20 0xe5e2ad271ceea56299d2965cf8222fcb65d5bb8e OP_EQUALVERIFY OP_CHECKSIG"}],"nLockTime":0}',
            $withdraw->getJsonTransaction()
        );
        $this->assertNull($withdraw->getRawSignedTransaction());
        $this->assertNull($withdraw->getJsonSignedTransaction());
        $this->assertEquals('32fa591b82cae594b286cd1f638af947e22e9aee72bfd42c68c1a872197d42e5', $withdraw->getWithdrawInputs()->first()->getTxid());
        $this->assertEquals('1MxXHgScDGaA7GaJY8bGa9MsCKU6iXaiRh', $withdraw->getWithdrawOutputs()->first()->getToAddress());
        $this->assertNull($withdraw->getChangeAddress());
        $this->assertTrue($withdraw->isSpendable());
    }

    /**
     * Mock Bitcoind calls because TravisCI does not have bitcoind server
     *
     * @deprecated The transaction is now builder with bitcore js script
     */
    private function mockBitcoind()
    {
        $inputs = [
            [
                'txid' => '32fa591b82cae594b286cd1f638af947e22e9aee72bfd42c68c1a872197d42e5',
                'vout' => 0
            ]
        ];

        $outputs = [
            '1MxXHgScDGaA7GaJY8bGa9MsCKU6iXaiRh' => 0.0002
        ];

        $bitcoind = $this->getMockBuilder('Nbobtc\Bitcoind\Bitcoind')
            ->disableOriginalConstructor()
            ->getMock();

        $bitcoind
            ->expects($this->once())
            ->method('createrawtransaction')
            ->with($this->equalTo($inputs), $this->equalTo($outputs))
            ->will($this->returnValue('0100000001e5427d1972a8c1682cd4bf72ee9a2ee247f98a631fcd86b294e5ca821b59fa320000000000ffffffff01204e0000000000001976a914e5e2ad271ceea56299d2965cf8222fcb65d5bb8e88ac00000000'))
        ;

        $this->getContainer()->set('bitcoind', $bitcoind);
    }

    private function addTransactionAndWithdrawOutput()
    {
        $address = $this->em
            ->getRepository('DizdaAppBundle:Address')
            ->findOneByValue('3MxR1yHVpfB7cXULzpetoyNVvUeqhoaJhE')
        ;

        $transaction = (new Transaction)
            ->setType(Transaction::TYPE_IN)
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
