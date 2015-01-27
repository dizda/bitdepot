<?php

namespace Dizda\Bundle\AppBundle\Tests\EventListener;

use Dizda\Bundle\AppBundle\Entity\Address;
use Dizda\Bundle\AppBundle\Entity\Deposit;
use Dizda\Bundle\AppBundle\EventListener\DepositListener;
use Doctrine\ORM\EntityManager;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTestCase;

/**
 * Class DepositListenerTest
 */
class DepositListenerTest extends ProphecyTestCase
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var \Dizda\Bundle\AppBundle\EventListener\DepositListener
     */
    private $listener;

    /**
     * DepositListener::onUpdate()
     */
    public function testOnUpdateProcessExpectedType()
    {
        $event = $this->prophesize('Dizda\Bundle\AppBundle\Event\DepositEvent');
        $deposit = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Deposit');
        $address = (new Address())
            ->setBalance('1.77')
        ;

        $event->getDeposit()->shouldBeCalled()->willReturn($deposit->reveal());
        $deposit->getAddressExternal()->shouldBeCalled()->willReturn($address);
        $deposit->getType()->shouldBeCalled()->willReturn(Deposit::TYPE_AMOUNT_EXPECTED);

        $deposit->setAmountFilled($address->getBalance())->shouldBeCalled();
        $deposit->getAmountExpected()->shouldBeCalled()->willReturn('1.7700000');
        $deposit->setIsFulfilled(true)->shouldBeCalled();
        $deposit->setIsOverfilled(true)->shouldNotBeCalled();
        $deposit->setQueueStatus(Deposit::QUEUE_STATUS_QUEUED)->shouldBeCalled();

        $this->listener->onUpdate($event->reveal());
    }

    /**
     * DepositListener::onUpdate()
     */
    public function testOnUpdateProcessExpectedTypeIsOverfilled()
    {
        $event = $this->prophesize('Dizda\Bundle\AppBundle\Event\DepositEvent');
        $deposit = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Deposit');
        $address = (new Address())
            ->setBalance('1.78')
        ;

        $event->getDeposit()->shouldBeCalled()->willReturn($deposit->reveal());
        $deposit->getAddressExternal()->shouldBeCalled()->willReturn($address);
        $deposit->getType()->shouldBeCalled()->willReturn(Deposit::TYPE_AMOUNT_EXPECTED);

        $deposit->setAmountFilled($address->getBalance())->shouldBeCalled();
        $deposit->getAmountExpected()->shouldBeCalled()->willReturn('1.7700000');
        $deposit->setIsFulfilled(true)->shouldBeCalled();
        $deposit->setIsOverfilled(true)->shouldBeCalled();
        $deposit->getId()->shouldBeCalled();
        $deposit->setQueueStatus(Deposit::QUEUE_STATUS_QUEUED)->shouldBeCalled();

        $this->listener->onUpdate($event->reveal());
    }

    /**
     * Instantiate
     *
     * @before
     */
    public function setUpObjects()
    {
        $this->logger       = $this->prophesize('Psr\Log\LoggerInterface');
        $this->em           = $this->prophesize('Doctrine\ORM\EntityManager');
        $this->listener     = new DepositListener(
            $this->logger->reveal(),
            $this->em->reveal()
        );
    }
}
