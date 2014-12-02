<?php

namespace Dizda\Bundle\AppBundle\Tests\Manager;

use Dizda\Bundle\AppBundle\Entity\Address;
use Dizda\Bundle\AppBundle\Entity\AddressTransaction;
use Dizda\Bundle\AppBundle\Entity\Application;
use Dizda\Bundle\AppBundle\Entity\WithdrawOutput;
use Dizda\Bundle\AppBundle\Manager\DepositManager;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTestCase;
use Dizda\Bundle\AppBundle\Manager\WithdrawManager;

/**
 * Class DepositManagerTest
 */
class WithdrawManagerTest extends ProphecyTestCase
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
        $app = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Application');
        $withdrawOutputRepo = $this->prophesize('Dizda\Bundle\AppBundle\Repository\WithdrawOutputRepository');

        $this->em->getRepository('DizdaAppBundle:WithdrawOutput')
            ->shouldBeCalled()
            ->willReturn($withdrawOutputRepo->reveal())
        ;

        $withdrawOutputRepo->getWhereWithdrawIsNull($app)
            ->shouldBeCalled()
            ->willReturn(null)
        ;

        $app->getGroupWithdrawsByQuantity()->shouldBeCalled()->willReturn(null);

        $return = $this->manager->search($app->reveal());

        $this->assertFalse($return);
    }

    /**
     * WithdrawManager::search()
     */
    public function testSearchWhereOutputsIsLowerThanGroupWithdrawsByQuantity()
    {
        $app = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Application');
        $withdrawOutputRepo = $this->prophesize('Dizda\Bundle\AppBundle\Repository\WithdrawOutputRepository');

        $this->em->getRepository('DizdaAppBundle:WithdrawOutput')
            ->shouldBeCalled()
            ->willReturn($withdrawOutputRepo->reveal())
        ;

        $withdrawOutputRepo->getWhereWithdrawIsNull($app)
            ->shouldBeCalled()
            ->willReturn([true, true]) // 2 outputs
        ;

        $app->getGroupWithdrawsByQuantity()->shouldBeCalled()->willReturn(3); // Waiting for 3 at minimum

        $return = $this->manager->search($app->reveal());

        $this->assertFalse($return);
    }

    /**
     * WithdrawManager::search()
     */
    public function testSearchSuccess()
    {
        $app = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Application');
        $withdrawOutputRepo = $this->prophesize('Dizda\Bundle\AppBundle\Repository\WithdrawOutputRepository');

        $this->em->getRepository('DizdaAppBundle:WithdrawOutput')
            ->shouldBeCalled()
            ->willReturn($withdrawOutputRepo->reveal())
        ;

        $withdrawOutputRepo->getWhereWithdrawIsNull($app)
            ->shouldBeCalled()
            ->willReturn([true, true, true]) // 3 outputs
        ;

        $app->getGroupWithdrawsByQuantity()->shouldBeCalled()->willReturn(3); // Waiting for 3 at minimum

        $return = $this->manager->search($app->reveal());

        $this->assertCount(3, $return);
    }


    public function testCreateSuccess()
    {
        $addressTransRepo = $this->prophesize('Dizda\Bundle\AppBundle\Repository\AddressTransactionRepository');

        $this->em->getRepository('DizdaAppBundle:AddressTransaction')
            ->shouldBeCalled()
            ->willReturn($addressTransRepo->reveal())
        ;

        $addressTransRepo->getSpendableTransactions()
            ->shouldBeCalled()
            ->willReturn([(new AddressTransaction())->setAmount('0.0002')])
        ;

        $this->em->persist(Argument::type('Dizda\Bundle\AppBundle\Entity\Withdraw'))->shouldBeCalled();
        $this->em->flush()->shouldBeCalled();

        $this->manager->create($this->getApp()->reveal(), $this->getOutputs());
    }

    public function testCreateSuccessInsufficientAmountAvailable()
    {
        $addressTransRepo = $this->prophesize('Dizda\Bundle\AppBundle\Repository\AddressTransactionRepository');

        $this->em->getRepository('DizdaAppBundle:AddressTransaction')
            ->shouldBeCalled()
            ->willReturn($addressTransRepo->reveal())
        ;

        $addressTransRepo->getSpendableTransactions()
            ->shouldBeCalled()
            ->willReturn([(new AddressTransaction())->setAmount('0.0001')])
        ;

        $this->em->persist(Argument::type('Dizda\Bundle\AppBundle\Entity\Withdraw'))->shouldNotBeCalled();
        $this->em->flush()->shouldNotBeCalled();

        $this->manager->create($this->getApp()->reveal(), $this->getOutputs());
    }

    private function getOutputs()
    {
        return [
            (new WithdrawOutput())
                ->setAmount('0.0001')
                ->setApplication($this->getApp()->reveal())
                ->setIsAccepted(true)
                ->setToAddress('lol')
        ];
    }

    private function getApp()
    {
        $app = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Application');
        $keychain = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Keychain');

        $app->reveal();
        $app->getKeychain()->willReturn($keychain->reveal());

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
