<?php

namespace Dizda\Bundle\BlockchainBundle\Tests\Command;

use Dizda\Bundle\AppBundle\Entity\Transaction;
use Dizda\Bundle\AppBundle\Request\PostDepositsRequest;
use Dizda\Bundle\AppBundle\Tests\BaseFunctionalTestCommand;

/**
 * Class BlockchainMonitorCommandTest
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class BlockchainMonitorCommandTest extends BaseFunctionalTestCommand
{

    public function testExecute()
    {
        $this->mockRabbitMq1();

        $count = $this->em->getRepository('DizdaAppBundle:Transaction')->findAll();
        $this->assertCount(2, $count);

        // Run the Command!
        $this->runCommand('dizda:blockchain:monitor', [], true);

        $transactions = $this->em->getRepository('DizdaAppBundle:Transaction')->findAll();
        $this->assertCount(3, $transactions);

        $transaction = $this->em->getRepository('DizdaAppBundle:Transaction')->find($transactions[2]->getId());
        $this->assertEquals('f2cbe3050e3dc70f73233de2fd100dacb5ac0834eb383136c5dec3096b044d28', $transaction->getTxid());
        $this->assertEquals(Transaction::TYPE_IN, $transaction->getType());
        $this->assertEquals('0.00020000', $transaction->getAmount());
        $this->assertTrue($transaction->getIsSpent());
    }

    /**
     * We're expecting 2 deposits
     */
    public function testExecuteWithSeveralDeposits()
    {
        $this->mockRabbitMq2();

        $data = [
            'application_id' => 1,
            'type'           => 1,
            'amount_expected'=> '77.00000000'
        ];
        $data = new PostDepositsRequest($data);

        $deposit = $this->getContainer()->get('dizda_app.manager.deposit')->create($data->options);
        $deposit->getAddressExternal()->setValue('33TbdkkSSW1FrUoDK4rhVUQKH2CrdEY613');
        $this->em->flush();

        $count = $this->em->getRepository('DizdaAppBundle:Transaction')->findAll();
        $this->assertCount(2, $count);

        // Run the Command!
        $this->runCommand('dizda:blockchain:monitor', [], true);

        $transactions = $this->em->getRepository('DizdaAppBundle:Transaction')->findAll();
        $this->assertCount(4, $transactions);

        $transaction = $this->em->getRepository('DizdaAppBundle:Transaction')->find($transactions[2]->getId());
        $this->assertEquals('f2cbe3050e3dc70f73233de2fd100dacb5ac0834eb383136c5dec3096b044d28', $transaction->getTxid());
        $this->assertEquals(Transaction::TYPE_IN, $transaction->getType());
        $this->assertEquals('0.00020000', $transaction->getAmount());
        $this->assertTrue($transaction->getIsSpent());

        $transaction = $this->em->getRepository('DizdaAppBundle:Transaction')->find($transactions[3]->getId());
        $this->assertEquals('2aad1c3e71c394046ba44e677f67e52c05d5ce8592f3ec513f7c4e3abf1e6631', $transaction->getTxid());
        $this->assertEquals(Transaction::TYPE_IN, $transaction->getType());
        $this->assertEquals('0.00360000', $transaction->getAmount());
        $this->assertTrue($transaction->getIsSpent());
    }

    private function mockRabbitMq1()
    {
        $producer1 = $this->getMockBuilder('OldSound\RabbitMqBundle\RabbitMq\Producer')
            ->disableOriginalConstructor()
            ->setMethods(array('publish'))
            ->getMock();

        $producer1
            ->expects($this->exactly(1))
            ->method('publish')
            ->withConsecutive(
                [$this->stringContains('i:'), $this->equalTo('')]
            )
        ;

//        $this->getContainer()->set('old_sound_rabbit_mq.deposit_callback_producer', $producer1); # Doesn't work, can not replace it directly from the container, dunno why
        $this->getContainer()->get('dizda_app.listener.deposit_entity')->setDepositProducer($producer1);
    }

    private function mockRabbitMq2()
    {
        $producer1 = $this->getMockBuilder('OldSound\RabbitMqBundle\RabbitMq\Producer')
            ->disableOriginalConstructor()
            ->setMethods(array('publish'))
            ->getMock();

        $producer1
            ->expects($this->exactly(2))
            ->method('publish')
            ->withConsecutive(
                [$this->stringContains('i:')],
                [$this->stringContains('i:')]
            )
        ;

        $this->getContainer()->get('dizda_app.listener.deposit_entity')->setDepositProducer($producer1);
    }
}
