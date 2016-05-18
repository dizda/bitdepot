<?php

namespace Dizda\Bundle\AppBundle\Tests\Manager;

use AppBundle\Tests\BasicUnitTest;
use Dizda\Bundle\AppBundle\Entity\Transaction;
use Dizda\Bundle\BlockchainBundle\Model\Insight\Transaction as InsightTransaction;
use Dizda\Bundle\BlockchainBundle\Model\Insight\TransactionInput;
use Dizda\Bundle\BlockchainBundle\Model\Insight\TransactionOutput;
use Doctrine\Common\Collections\ArrayCollection;
use Prophecy\Argument;
use Dizda\Bundle\AppBundle\Manager\AddressManager;

/**
 * Class AddressManagerTest
 */
class AddressManagerTest extends BasicUnitTest
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
     * @var \Dizda\Bundle\AppBundle\Manager\AddressManager
     */
    private $manager;

    /**
     * @var \Dizda\Bundle\AppBundle\Service\AddressService
     */
    private $addressService;

    /**
     * AddressManager::create()
     */
    public function testCreateWithNewAddress()
    {
        $app = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Application');
        $addressRepo = $this->prophesize('Dizda\Bundle\AppBundle\Repository\AddressRepository');

        $this->em->getRepository('DizdaAppBundle:Address')->shouldBeCalled()->willReturn($addressRepo->reveal());
        $addressRepo->getLastDerivation($app, true)->shouldBeCalled();

        $pubKeys = [
            '032ed3783bb08f4f70299fe38e680637d6e1693f12f32891574dc08a0964481f0f',
            '03592bf1c56e474d2a51ce7060a9c910ff5014f9c3723bd9997c5a08b1fe02f451',
            '03f6ece630eb37c27e3f54e52d779db9f3f832621a1a6333489f88b09783d95d31'
        ];

        $this->addressService
            ->generateHDMultisigAddress($app, true, 0)
            ->shouldBeCalled()
            ->willReturn([
                'address'      => 'add3ssBitch',
                'redeemScript' => 'redeemBitch',
                'pubKeys'      => $pubKeys
            ])
        ;

        $this->em->persist(Argument::type('Dizda\Bundle\AppBundle\Entity\Address'))->shouldBeCalledTimes(1);

        $return = $this->manager->create($app->reveal(), true);
        $this->assertEquals('add3ssBitch', $return->getValue());
        $this->assertEquals('redeemBitch', $return->getRedeemScript());
        $this->assertEquals($pubKeys, $return->getPubKeys());
    }

    /**
     * AddressManager::saveTransactions()
     */
    public function testSaveTransactionsOutputs()
    {
        $address = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Address');
        $address->getValue()->shouldBeCalled()->willReturn('addressExpectedIN');
        $address->hasTransaction('transactionId', Transaction::TYPE_IN, 6)
            ->shouldBeCalled()
            ->willReturn(false)
        ; // check that doesn't match the output

        $this->em->persist(Argument::type('Dizda\Bundle\AppBundle\Entity\Transaction'))->shouldBeCalledTimes(1);

        $return = $this->manager->saveTransactions($address->reveal(), new ArrayCollection($this->getDummyTransactionsOutput()));
        $this->assertCount(1, $return);
    }

    /**
     * AddressManager::saveTransactions()
     */
    public function testSaveTransactionsHasTransactionsContinueOutput()
    {
        $address = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Address');
        $address->getValue()->shouldBeCalled()->willReturn('addressExpectedIN');
        $address->hasTransaction('transactionId', Transaction::TYPE_IN, 6)
            ->shouldBeCalled()
            ->willReturn(true)
        ; // check that match the output

        $this->em->persist(Argument::type('Dizda\Bundle\AppBundle\Entity\Transaction'))->shouldNotBeCalled();

        $return = $this->manager->saveTransactions($address->reveal(), new ArrayCollection($this->getDummyTransactionsNone()));
        $this->assertCount(0, $return);
    }

    /**
     * @return array
     */
    public function getDummyTransactionsInput()
    {
        return [
            (new InsightTransaction())
                ->setTxid('transactionId')
                ->setInputs([
                    (new TransactionInput())
                        ->setAddress('addressExpected')
                        ->setIndex(5)
                ])
                ->setOutputs(
                    (new TransactionOutput())
                )
        ];
    }

    /**
     * @return array
     */
    public function getDummyTransactionsOutput()
    {
        return [
            (new InsightTransaction())
                ->setTxid('transactionId')
                ->setInputs([
                    (new TransactionInput())
                ])
                ->setOutputs([
                    (new TransactionOutput())
                        ->setAddresses(['addresses' => ['addressExpectedIN']])
                        ->setIndex(6)
                ])
        ];
    }

    /**
     * @return array
     */
    public function getDummyTransactionsNone()
    {
        return [
            (new InsightTransaction())
                ->setTxid('transactionId')
                ->setInputs([
                    (new TransactionInput())
                        ->setAddress('addressExpected')
                        ->setIndex(5)
                ])
                ->setOutputs([
                    (new TransactionOutput())
                        ->setAddresses(['addresses' => ['addressExpectedIN']])
                        ->setIndex(6)
                ])
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
        $this->dispatcher   = $this->prophesize('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->addressService = $this->prophesize('Dizda\Bundle\AppBundle\Service\AddressService');
        $this->manager      = new AddressManager(
            $this->em->reveal(),
            $this->logger->reveal(),
            $this->dispatcher->reveal(),
            $this->addressService->reveal()
        );
    }
}
