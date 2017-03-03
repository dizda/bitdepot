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
        $this->assertCount(3, $transactions);

        $transaction = $this->em->getRepository('DizdaAppBundle:Transaction')->find($transactions[2]->getId());
        $this->assertEquals('f2cbe3050e3dc70f73233de2fd100dacb5ac0834eb383136c5dec3096b044d28', $transaction->getTxid());
        $this->assertEquals(Transaction::TYPE_IN, $transaction->getType());
        $this->assertEquals('0.00020000', $transaction->getAmount());
        $this->assertTrue($transaction->getIsSpent());
    }
}
