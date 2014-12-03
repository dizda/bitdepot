<?php

namespace Dizda\Bundle\AppBundle\Tests\EventListener;

use Dizda\Bundle\AppBundle\Entity\AddressTransaction;
use Dizda\Bundle\AppBundle\Entity\Withdraw;
use Dizda\Bundle\AppBundle\Entity\WithdrawOutput;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTestCase;
use Dizda\Bundle\AppBundle\EventListener\WithdrawListener;

/**
 * Class WithdrawListenerTest
 */
class WithdrawListenerTest extends ProphecyTestCase
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
     * @var \Nbobtc\Bitcoind\Bitcoind
     */
    private $bitcoind;

    /**
     * @var \Dizda\Bundle\AppBundle\Event\WithdrawEvent
     */
    private $withdrawEvent;

    /**
     * @var \Dizda\Bundle\AppBundle\EventListener\WithdrawListener
     */
    private $manager;

    /**
     * WithdrawListener::create()
     */
    public function testOnCreate()
    {
        $withdraw = $this->getSpendableWithdraw();

        $this->withdrawEvent->getWithdraw()->shouldBeCalled()->willReturn($withdraw);

        $this->bitcoind->createrawtransaction(
            Argument::exact($this->getExpectedInputs()),
            Argument::exact($this->getExpectedOutputs())
        )->shouldBeCalled()->willReturn('R4wTr4nsact!on');

        $this->manager->onCreate($this->withdrawEvent->reveal());
        $this->assertEquals('R4wTr4nsact!on', $withdraw->getRawTransaction());
    }

    private function getSpendableWithdraw()
    {
        return (new Withdraw())
            ->setTotalInputs('0.0004')
            ->setTotalOutputs('0.0003')
            ->setFees('0.0001')
            ->addWithdrawInput(
                (new AddressTransaction())
                    ->setTxid('431c5231114ce2d00125ea4a88f4e4637b80fef1117a0b20606204e45cc3678f')
                    ->setIndex(1)
            )
            ->addWithdrawInput(
                (new AddressTransaction())
                    ->setTxid('be0f6dc2cd45c0fcfaaf2d7aa19190bc2fcb5481b0a21ac7f309cecd5e75db9f')
                    ->setIndex(0)
            )
            ->addWithdrawOutput(
                (new WithdrawOutput())
                    ->setToAddress('1LGTbdVSEbD9C37qXcpvVJ1egdBu8jYSeV')
                    ->setAmount('0.0001')
            )
            ->addWithdrawOutput(
                (new WithdrawOutput())
                    ->setToAddress('1Cxtev7KLyEen5UxqsBYn6JqcZREm28DXh')
                    ->setAmount('0.0002')
            )
        ;
    }

    private function getExpectedInputs()
    {
        return [
            [
                'txid' => '431c5231114ce2d00125ea4a88f4e4637b80fef1117a0b20606204e45cc3678f',
                'vout' => 1
            ],
            [
                'txid' => 'be0f6dc2cd45c0fcfaaf2d7aa19190bc2fcb5481b0a21ac7f309cecd5e75db9f',
                'vout' => 0
            ]
        ];
    }

    private function getExpectedOutputs()
    {
        return [
            '1LGTbdVSEbD9C37qXcpvVJ1egdBu8jYSeV' => 0.0001,
            '1Cxtev7KLyEen5UxqsBYn6JqcZREm28DXh' => 0.0002
        ];
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
        $this->bitcoind     = $this->prophesize('Nbobtc\Bitcoind\Bitcoind');
        $this->withdrawEvent = $this->prophesize('Dizda\Bundle\AppBundle\Event\WithdrawEvent');
        $this->manager      = new WithdrawListener(
            $this->logger->reveal(),
            $this->bitcoind->reveal(),
            $this->em->reveal()
        );
    }
}
