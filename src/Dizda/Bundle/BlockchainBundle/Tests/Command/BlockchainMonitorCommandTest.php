<?php

namespace Dizda\Bundle\BlockchainBundle\Tests\Command;

use Dizda\Bundle\AppBundle\Entity\AddressTransaction;
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
        $count = $this->em->getRepository('DizdaAppBundle:AddressTransaction')->findAll();
        $this->assertCount(2, $count);

        // Run the Command!
        $this->runCommand('dizda:blockchain:monitor', [], true);

        $transactions = $this->em->getRepository('DizdaAppBundle:AddressTransaction')->findAll();
        $this->assertCount(4, $transactions);

        $transaction = $this->em->getRepository('DizdaAppBundle:AddressTransaction')->find($transactions[2]->getId());
        $this->assertEquals('f2cbe3050e3dc70f73233de2fd100dacb5ac0834eb383136c5dec3096b044d28', $transaction->getTxid());
        $this->assertEquals(AddressTransaction::TYPE_IN, $transaction->getType());
        $this->assertEquals('0.00020000', $transaction->getAmount());
        $this->assertFalse($transaction->getIsSpent()); // need to fix this

        $transaction = $this->em->getRepository('DizdaAppBundle:AddressTransaction')->find($transactions[3]->getId());
        $this->assertEquals('154e4b57962848742c579b564465730e23a8d0a4ba83ebbcdedccf0ced80e98a', $transaction->getTxid());
        $this->assertEquals(AddressTransaction::TYPE_OUT, $transaction->getType());
        $this->assertEquals('0.00020000', $transaction->getAmount());
        $this->assertEquals(1, $transaction->getIndex());
        $this->assertFalse($transaction->getIsSpent()); // need to fix this
    }
}
