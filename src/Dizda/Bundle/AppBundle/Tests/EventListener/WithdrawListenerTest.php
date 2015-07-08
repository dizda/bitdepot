<?php

namespace Dizda\Bundle\AppBundle\Tests\EventListener;

use Dizda\Bundle\AppBundle\Entity\Address;
use Dizda\Bundle\AppBundle\Entity\Transaction;
use Dizda\Bundle\AppBundle\Entity\Application;
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
     * @var \Dizda\Bundle\AppBundle\Manager\AddressManager
     */
    private $addressManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Dizda\Bundle\AppBundle\Service\BitcoreService
     */
    private $bitcoreService;

    /**
     * @var \Dizda\Bundle\AppBundle\Event\WithdrawEvent
     */
    private $withdrawEvent;

    /**
     * @var \OldSound\RabbitMqBundle\RabbitMq\Producer
     */
    private $producer;

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

        $this->bitcoreService->buildTransaction(
            Argument::exact($withdraw->getWithdrawInputs()),
            Argument::exact($withdraw->getWithdrawOutputs()),
            Argument::exact($withdraw->getChangeAddress())
        )->shouldBeCalled()->willReturn([
            'fees'             => '0.00090000',
            'json_transaction' => 'jsonTransaction',
            'raw_transaction'  => 'R4wTr4nsact!on',
            'change_amount'    => '0.00000000'
        ]);

        $this->manager->onCreate($this->withdrawEvent->reveal());
        $this->assertEquals('jsonTransaction', $withdraw->getJsonTransaction());
        $this->assertEquals('R4wTr4nsact!on', $withdraw->getRawTransaction());
        $this->assertEquals('0.00090000', $withdraw->getFees());
        $this->assertEquals('0.00000000', $withdraw->getChangeAddressAmount());
        $this->assertNull($withdraw->getJsonSignedTransaction());
        $this->assertNull($withdraw->getRawSignedTransaction());
        $this->assertNull($withdraw->getChangeAddress());
    }

    /**
     * WithdrawListener::create()
     */
    public function testOnCreateWithChangeAddress()
    {
        $repo     = $this->prophesize('Dizda\Bundle\AppBundle\Repository\AddressRepository');
        $withdraw = $this->getSpentWithdrawWithChangeAddress();
        $changeAddress = (new Address())
            ->setValue('1MxXHgScDGaA7GaJY8bGa9MsCKU6iXaiRh')
        ;

        $this->withdrawEvent->getWithdraw()->shouldBeCalled()->willReturn($withdraw);

        $this->addressManager->create(
            Argument::type('Dizda\Bundle\AppBundle\Entity\Application'),
            Argument::exact(false)
        )->shouldBeCalled()->willReturn($changeAddress);

        $this->bitcoreService->buildTransaction(
            Argument::exact($withdraw->getWithdrawInputs()),
            Argument::exact($withdraw->getWithdrawOutputs()),
            Argument::exact($changeAddress)
        )->shouldBeCalled()->willReturn([
            'fees'             => '0.00010000',
            'json_transaction' => 'jsonTransaction',
            'raw_transaction'  => 'R4wTr4nsact!on',
            'change_amount'    => '0.00123400'
        ]);

        $this->manager->onCreate($this->withdrawEvent->reveal());
        $this->assertEquals('0.00010000', $withdraw->getFees());
        $this->assertEquals('R4wTr4nsact!on', $withdraw->getRawTransaction());
        $this->assertEquals('jsonTransaction', $withdraw->getJsonTransaction());
        $this->assertEquals($changeAddress, $withdraw->getChangeAddress());
        $this->assertEquals('0.00123400', $withdraw->getChangeAddressAmount());
        $this->assertNotNull($withdraw->getChangeAddress());
    }

    /**
     * WithdrawListener::create()
     *
     * @expectedException \Dizda\Bundle\AppBundle\Exception\InsufficientAmountException
     */
    public function testOnCreateThrowException()
    {
        $withdraw = $this->getCantSpentWithdraw();

        $this->withdrawEvent->getWithdraw()->shouldBeCalled()->willReturn($withdraw);

        $this->manager->onCreate($this->withdrawEvent->reveal());
    }

    /**
     * WithdrawListener::send()
     */
    public function testOnSend()
    {
        $withdraw = $this->getSpendableWithdraw();
        $withdraw->setRawSignedTransaction('coucou');

        $this->withdrawEvent->getWithdraw()->shouldBeCalled()->willReturn($withdraw);

        $this->bitcoreService->broadcastTransaction(
            Argument::exact('coucou')
        )->shouldBeCalled()->willReturn('tr4ns4ct!onId');

        $this->manager->onSend($this->withdrawEvent->reveal());
        $this->assertEquals('tr4ns4ct!onId', $withdraw->getTxid());
        $this->assertNotNull($withdraw->getWithdrawedAt());

        // check that inputs are marked as spent
        foreach ($withdraw->getWithdrawInputs() as $input) {
            $this->assertTrue($input->getIsSpent());
            $this->assertEquals('0', $input->getAddress()->getBalance());
        }
    }

    /**
     * @return Withdraw
     */
    private function getSpendableWithdraw()
    {
        return (new Withdraw())
            ->setTotalInputs('0.0004')
            ->setTotalOutputs('0.0003')
            ->setFees('0.0001')
            ->addWithdrawInput(
                (new Transaction())
                    ->setTxid('431c5231114ce2d00125ea4a88f4e4637b80fef1117a0b20606204e45cc3678f')
                    ->setIndex(1)
                    ->setAddress(new Address())
            )
            ->addWithdrawInput(
                (new Transaction())
                    ->setTxid('be0f6dc2cd45c0fcfaaf2d7aa19190bc2fcb5481b0a21ac7f309cecd5e75db9f')
                    ->setIndex(0)
                    ->setAddress(new Address())
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

    /**
     * @return Withdraw
     */
    private function getCantSpentWithdraw()
    {
        return (new Withdraw())
            ->setTotalInputs('0.0003')
            ->setTotalOutputs('0.0003')
            ->setFees('0.0001')
            ->addWithdrawInput(
                (new Transaction())
                    ->setTxid('431c5231114ce2d00125ea4a88f4e4637b80fef1117a0b20606204e45cc3678f')
                    ->setIndex(1)
            )
            ->addWithdrawInput(
                (new Transaction())
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

    /**
     * @return Withdraw
     */
    private function getSpentWithdrawWithChangeAddress()
    {
        return (new Withdraw())
            ->setTotalInputs('0.0005')
            ->setTotalOutputs('0.0003')
            ->setFees('0.0001')
            ->addWithdrawInput(
                (new Transaction())
                    ->setTxid('431c5231114ce2d00125ea4a88f4e4637b80fef1117a0b20606204e45cc3678f')
                    ->setIndex(1)
            )
            ->addWithdrawInput(
                (new Transaction())
                    ->setTxid('be0f6dc2cd45c0fcfaaf2d7aa19190bc2fcb5481b0a21ac7f309cecd5e75db9f')
                    ->setIndex(0)
            )
            ->addWithdrawOutput(
                (new WithdrawOutput())
                    ->setToAddress('1LGTbdVSEbD9C37qXcpvVJ1egdBu8jYSeV')
                    ->setAmount('0.0001')
                    ->setApplication(new Application())
            )
            ->addWithdrawOutput(
                (new WithdrawOutput())
                    ->setToAddress('1Cxtev7KLyEen5UxqsBYn6JqcZREm28DXh')
                    ->setAmount('0.0002')
            )
        ;
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    private function getExpectedOutputs()
    {
        return [
            '1LGTbdVSEbD9C37qXcpvVJ1egdBu8jYSeV' => 0.0001,
            '1Cxtev7KLyEen5UxqsBYn6JqcZREm28DXh' => 0.0002
        ];
    }

    /**
     * @return array
     */
    private function getExpectedOutputsWithChangeAddress()
    {
        return [
            '1LGTbdVSEbD9C37qXcpvVJ1egdBu8jYSeV' => 0.0001,
            '1Cxtev7KLyEen5UxqsBYn6JqcZREm28DXh' => 0.0002,
            '1MxXHgScDGaA7GaJY8bGa9MsCKU6iXaiRh' => 0.0001
        ];
    }

    /**
     * Instantiate
     *
     * @before
     */
    public function setUpObjects()
    {
        $this->addressManager = $this->prophesize('Dizda\Bundle\AppBundle\Manager\AddressManager');
        $this->logger       = $this->prophesize('Psr\Log\LoggerInterface');
        $this->withdrawEvent = $this->prophesize('Dizda\Bundle\AppBundle\Event\WithdrawEvent');
        $this->producer      = $this->prophesize('OldSound\RabbitMqBundle\RabbitMq\Producer');
        $this->producer2      = $this->prophesize('OldSound\RabbitMqBundle\RabbitMq\Producer');
        $this->bitcoreService = $this->prophesize('Dizda\Bundle\AppBundle\Service\BitcoreService');
        $this->manager      = new WithdrawListener(
            $this->logger->reveal(),
            $this->addressManager->reveal(),
            $this->bitcoreService->reveal(),
            $this->producer->reveal(),
            $this->producer2->reveal()
        );
    }
}
