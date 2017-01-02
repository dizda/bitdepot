<?php

namespace Dizda\Bundle\BlockchainBundle\Tests\Manager;

use Dizda\Bundle\AppBundle\Tests\BasicUnitTest;
use Dizda\Bundle\AppBundle\Entity\Address;
use Dizda\Bundle\AppBundle\Entity\Transaction;
use Dizda\Bundle\AppBundle\Entity\Application;
use Doctrine\Common\Collections\ArrayCollection;
use Prophecy\Argument;

class BlockchainManagerTest extends BasicUnitTest
{
    /**
     * @var \Dizda\Bundle\BlockchainBundle\Blockchain\BlockchainProvider
     */
    private $provider;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Dizda\Bundle\AppBundle\Manager\AddressManager
     */
    private $addressManager;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var \Dizda\Bundle\BlockchainBundle\Manager\BlockchainManager
     */
    private $manager;

    public function testMonitorOpenDeposits()
    {
        $repo              = $this->prophesize('Dizda\Bundle\AppBundle\Repository\DepositRepository');
        $deposit           = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Deposit');
        $blockchainWatcher = $this->prophesize('Dizda\Bundle\BlockchainBundle\Blockchain\BlockchainWatcherInterface');
        $address           = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Address');
        $addressFromBc     = $this->prophesize('Dizda\Bundle\BlockchainBundle\Model\AddressAbstract');

        $transactions = new ArrayCollection([new Transaction()]);
        $application  = new Application();
        $application->setConfirmationsRequired(2);


        // Start tests
        $this->em->getRepository('DizdaAppBundle:Deposit')->shouldBeCalled()->willReturn($repo->reveal());
        $repo->getOpenDeposits()->shouldBeCalled()->willReturn([ $deposit->reveal() ]);

        $deposit->getAddressExternal()->shouldBeCalledTimes(4)->willReturn($address->reveal());

        $this->provider->isAddressChanged(Argument::type('Dizda\Bundle\AppBundle\Entity\Address'))
            ->shouldBeCalled()
            ->willReturn($addressFromBc->reveal())
        ;

        $this->provider->getBlockchain()->shouldBeCalled()->willReturn($blockchainWatcher->reveal());
        $deposit->getApplication()->shouldBeCalled()->willReturn($application);
        $address->getValue()->shouldBeCalled()->willReturn('31is8U4NQigkLLfJF7hXoy9XE7r8iVDkFc');

        $blockchainWatcher->getTransactionsByAddress(
            Argument::exact('31is8U4NQigkLLfJF7hXoy9XE7r8iVDkFc'),
            Argument::exact(2)
        )->shouldBeCalled()->willReturn($transactions);

        $this->addressManager
            ->saveTransactions(Argument::type('Dizda\Bundle\AppBundle\Entity\Address'), $transactions)
            ->shouldBeCalled()
            ->willReturn([ $transactions ])
        ;

        $addressFromBc->getBalance()->shouldBeCalled()->willReturn('0.0001');
        $address->setBalance(Argument::exact('0.0001'))->shouldBeCalled();

        $this->dispatcher->dispatch(Argument::any(), Argument::type('Dizda\Bundle\AppBundle\Event\DepositEvent'))
            ->shouldBeCalled()
        ;

        $this->manager->monitorOpenDeposits();
    }

    public function testMonitorChangeAddresses()
    {
        $repo              = $this->prophesize('Dizda\Bundle\AppBundle\Repository\WithdrawRepository');
        $withdraw          = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Withdraw');
        $blockchainWatcher = $this->prophesize('Dizda\Bundle\BlockchainBundle\Blockchain\BlockchainWatcherInterface');
        $address           = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Address');
        $addressFromBc     = $this->prophesize('Dizda\Bundle\BlockchainBundle\Model\AddressAbstract');

        $transactions = new ArrayCollection([new Transaction()]);
        $application  = new Application();
        $application->setConfirmationsRequired(2);


        // Start tests
        $this->em->getRepository('DizdaAppBundle:Withdraw')->shouldBeCalled()->willReturn($repo->reveal());
        $repo->getChangeAddressesMonitored()->shouldBeCalled()->willReturn([ $withdraw->reveal() ]);

        $withdraw->getChangeAddress()->shouldBeCalledTimes(4)->willReturn($address->reveal());

        $this->provider->isAddressChanged(Argument::type('Dizda\Bundle\AppBundle\Entity\Address'))
            ->shouldBeCalled()
            ->willReturn($addressFromBc->reveal())
        ;

        $this->provider->getBlockchain()->shouldBeCalled()->willReturn($blockchainWatcher->reveal());
//        $deposit->getApplication()->shouldBeCalled()->willReturn($application);
        $address->getValue()->shouldBeCalled()->willReturn('31is8U4NQigkLLfJF7hXoy9XE7r8iVDkFc');

        $blockchainWatcher->getTransactionsByAddress(
            Argument::exact('31is8U4NQigkLLfJF7hXoy9XE7r8iVDkFc'),
            Argument::any()
        )->shouldBeCalled()->willReturn($transactions);

        $this->addressManager
            ->saveTransactions(Argument::type('Dizda\Bundle\AppBundle\Entity\Address'), $transactions)
            ->shouldBeCalled()
            ->willReturn([ $transactions ])
        ;

        $addressFromBc->getBalance()->shouldBeCalled()->willReturn('0.0001');
        $address->setBalance(Argument::exact('0.0001'))->shouldBeCalled();

//        $this->dispatcher->dispatch(Argument::any(), Argument::type('Dizda\Bundle\AppBundle\Event\DepositEvent'))
//            ->shouldBeCalled()
//        ;

        $this->manager->monitorChangeAddresses();
    }

    /**
     * Instantiate
     *
     * @before
     */
    public function setUpObjects()
    {
        $this->provider       = $this->prophesize('Dizda\Bundle\BlockchainBundle\Blockchain\BlockchainProvider');
        $this->em             = $this->prophesize('Doctrine\ORM\EntityManager');
        $this->addressManager = $this->prophesize('Dizda\Bundle\AppBundle\Manager\AddressManager');
        $this->dispatcher     = $this->prophesize('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->manager        = new \Dizda\Bundle\BlockchainBundle\Manager\BlockchainManager(
            $this->provider->reveal(),
            $this->em->reveal(),
            $this->addressManager->reveal(),
            $this->dispatcher->reveal()
        );
    }

}
