<?php

namespace Dizda\Bundle\AppBundle\Tests\Manager;

use AppBundle\Tests\BasicUnitTest;
use Dizda\Bundle\AppBundle\Entity\Transaction;
use Dizda\Bundle\AppBundle\Entity\Application;
use Dizda\Bundle\AppBundle\Entity\Identity;
use Dizda\Bundle\AppBundle\Entity\Keychain;
use Dizda\Bundle\AppBundle\Entity\PubKey;
use Dizda\Bundle\AppBundle\Entity\WithdrawOutput;
use Doctrine\Common\Collections\ArrayCollection;
use Prophecy\Argument;
use Dizda\Bundle\AppBundle\Request\PostWithdrawRequest;

/**
 * Class WithdrawManagerTest
 */
class WithdrawManagerTest extends BasicUnitTest
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var \Dizda\Bundle\AppBundle\Manager\WithdrawManager
     */
    private $manager;

    /**
     * WithdrawManager::search()
     */
    public function testSearchWithGroupWithdrawsByQuantityIsNull()
    {
        $keychain = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Keychain');
        $withdrawOutputRepo = $this->prophesize('Dizda\Bundle\AppBundle\Repository\WithdrawOutputRepository');

        $this->em->getRepository('DizdaAppBundle:WithdrawOutput')
            ->shouldBeCalled()
            ->willReturn($withdrawOutputRepo->reveal())
        ;

        $withdrawOutputRepo->getWhereWithdrawIsNull($keychain)
            ->shouldBeCalled()
            ->willReturn(null)
        ;

        $keychain->getGroupWithdrawsByQuantity()->shouldBeCalled()->willReturn(null);

        $return = $this->manager->search($keychain->reveal());

        $this->assertFalse($return);
    }

    /**
     * WithdrawManager::search()
     */
    public function testSearchWhereOutputsIsLowerThanGroupWithdrawsByQuantity()
    {
        $keychain = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Keychain');
        $withdrawOutputRepo = $this->prophesize('Dizda\Bundle\AppBundle\Repository\WithdrawOutputRepository');

        $this->em->getRepository('DizdaAppBundle:WithdrawOutput')
            ->shouldBeCalled()
            ->willReturn($withdrawOutputRepo->reveal())
        ;

        $withdrawOutputRepo->getWhereWithdrawIsNull($keychain)
            ->shouldBeCalled()
            ->willReturn([true, true]) // 2 outputs
        ;

        $keychain->getGroupWithdrawsByQuantity()->shouldBeCalled()->willReturn(3); // Waiting for 3 at minimum

        $return = $this->manager->search($keychain->reveal());

        $this->assertFalse($return);
    }

    /**
     * WithdrawManager::search()
     */
    public function testSearchSuccess()
    {
        $keychain = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Keychain');
        $withdrawOutputRepo = $this->prophesize('Dizda\Bundle\AppBundle\Repository\WithdrawOutputRepository');

        $this->em->getRepository('DizdaAppBundle:WithdrawOutput')
            ->shouldBeCalled()
            ->willReturn($withdrawOutputRepo->reveal())
        ;

        $withdrawOutputRepo->getWhereWithdrawIsNull($keychain)
            ->shouldBeCalled()
            ->willReturn([true, true, true]) // 3 outputs
        ;

        $keychain->getGroupWithdrawsByQuantity()->shouldBeCalled()->willReturn(3); // Waiting for 3 at minimum

        $return = $this->manager->search($keychain->reveal());

        $this->assertCount(3, $return);
    }

    /**
     * WithdrawManager::create()
     */
    public function testCreateSuccessWithNoChangeAddress()
    {
        $addressTransRepo = $this->prophesize('Dizda\Bundle\AppBundle\Repository\TransactionRepository');

        $this->em->getRepository('DizdaAppBundle:Transaction')
            ->shouldBeCalled()
            ->willReturn($addressTransRepo->reveal())
        ;

        $addressTransRepo->getSpendableTransactions()
            ->shouldBeCalled()
            ->willReturn([(new Transaction())->setAmount('0.0002')])
        ;

        $this->em->persist(Argument::type('Dizda\Bundle\AppBundle\Entity\Withdraw'))->shouldBeCalled();
        $this->em->flush()->shouldBeCalled();

        $return = $this->manager->create($this->getKeychain()->reveal(), $this->getOutputs());
        $this->assertEquals('0.00020000', $return->getTotalInputs());
        $this->assertEquals('0.00010000', $return->getTotalOutputs());
        $this->assertEquals('0.00010000', $return->getFees());
        $this->assertNull($return->getChangeAddressAmount());
        $this->assertNull($return->getChangeAddress());
    }

    /**
     * WithdrawManager::create()
     */
    public function testCreateSuccessInsufficientAmountAvailable()
    {
        $addressTransRepo = $this->prophesize('Dizda\Bundle\AppBundle\Repository\TransactionRepository');

        $this->em->getRepository('DizdaAppBundle:Transaction')
            ->shouldBeCalled()
            ->willReturn($addressTransRepo->reveal())
        ;

        $addressTransRepo->getSpendableTransactions()
            ->shouldBeCalled()
            ->willReturn([(new Transaction())->setAmount('0.0001')])
        ;

        $this->em->persist(Argument::type('Dizda\Bundle\AppBundle\Entity\Withdraw'))->shouldNotBeCalled();
        $this->em->flush()->shouldNotBeCalled();

        $return = $this->manager->create($this->getKeychain()->reveal(), $this->getOutputs());
        $this->assertNull($return);
    }

    /**
     * WithdrawManager::save()
     */
    public function testSave()
    {
        $withdraw = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Withdraw');
        $repo     = $this->prophesize('Doctrine\ORM\EntityRepository');

        $withdrawSubmitted = [
            'id'                     => 22,
            'raw_transaction'        => 'rawTransactionBITCH',
            'keychain'               => [],
            'total_inputs'           => '0.0003',
            'total_outputs'          => '0.0001',
            'withdraw_inputs'        => [],
            'withdraw_outputs'       => [],
            // Important fields :
            'raw_signed_transaction' => 'rawSignedTransactionBITCH',
            'json_signed_transaction' => 'jsonSignedTransactionBITCH',
            'signed_by'               => 'JESSEEPINKM4n',
            'is_signed'               => true
        ];
        $withdrawSubmitted = (new PostWithdrawRequest($withdrawSubmitted))->options;

        $keychain = new Keychain();
        $identity = (new Identity())->setKeychain($keychain);

        $withdraw->setRawSignedTransaction(Argument::exact('rawSignedTransactionBITCH'))->shouldBeCalled();
        $withdraw->setJsonSignedTransaction(Argument::exact('jsonSignedTransactionBITCH'))->shouldBeCalled();
        $withdraw->getKeychain()->shouldBeCalled()->willReturn($keychain);

        $this->em->getRepository('DizdaAppBundle:Identity')
            ->shouldBeCalled()
            ->willReturn($repo->reveal())
        ;

        $repo->findOneBy([
            'publicKey' => 'JESSEEPINKM4n',
            'keychain'  => $keychain
        ])->shouldBeCalled()->willReturn($identity);

        $withdraw->addSignature(Argument::type('Dizda\Bundle\AppBundle\Entity\Identity'))->shouldBeCalled();
        $withdraw->setIsSigned(true)->shouldBeCalled();


        $this->manager->save($withdraw->reveal(), $withdrawSubmitted);
    }

    /**
     * @return array
     */
    private function getOutputs()
    {
        return [
            (new WithdrawOutput())
                ->setAmount('0.0001')
                ->setApplication($this->getApplication()->reveal())
                ->setIsAccepted(true)
                ->setToAddress('lol')
        ];
    }

    /**
     * @return \Dizda\Bundle\AppBundle\Entity\Keychain
     */
    private function getKeychain()
    {
        $keychain = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Keychain');

        $keychain->getApplications()->willReturn([ $this->getApplication()->reveal() ]);
        $keychain->reveal();

        return $keychain;
    }

    private function getApplication()
    {
        $app = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Application');

        return $app;
    }

    /**
     * Instantiate
     *
     * @before
     */
    public function setUpObjects()
    {
        $this->em           = $this->prophesize('Doctrine\ORM\EntityManager');
        $this->logger       = $this->prophesize('Psr\Log\LoggerInterface');
        $this->dispatcher   = $this->prophesize('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->manager      = new \Dizda\Bundle\AppBundle\Manager\WithdrawManager(
            $this->em->reveal(),
            $this->logger->reveal(),
            $this->dispatcher->reveal()
        );
    }
}
