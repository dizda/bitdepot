<?php

namespace Dizda\Bundle\BlockchainBundle\Tests\Blockchain;

use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTestCase;
use Dizda\Bundle\BlockchainBundle\Blockchain\BlockchainProvider;

/**
 * Class BlockchainProviderTest
 */
class BlockchainProviderTest extends ProphecyTestCase
{
    /**
     * @var \Dizda\Bundle\BlockchainBundle\Blockchain\BlockchainWatcherInterface
     */
    private $watcher;

    /**
     * @var BlockchainProvider
     */
    private $provider;

    /**
     * BlockchainProvider::isAddressChanged()
     */
    public function testIsAddressChangedReturnFalse()
    {
        $address = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Address');
        $addressBlockchain = $this->prophesize('Dizda\Bundle\BlockchainBundle\Model\AddressAbstract');
        $arrayColl = $this->prophesize('Doctrine\Common\Collections\ArrayCollection');


        $address->getValue()->shouldBeCalled()->willReturn('pimpMyAddress');

        $this->watcher->getAddress(Argument::exact('pimpMyAddress'), Argument::exact(false))
            ->shouldBeCalled()
            ->willReturn($addressBlockchain->reveal())
        ;

        $address->getTransactions()
            ->shouldBeCalled()
            ->willReturn($arrayColl->reveal())
        ;

        $addressBlockchain->getTxApperances()->shouldBeCalled()->willReturn(2);
        $arrayColl->count()->shouldBeCalled()->willReturn(2);

        $return = $this->provider->isAddressChanged($address->reveal());
        $this->assertFalse($return);
    }

    /**
     * BlockchainProvider::isAddressChanged()
     */
    public function testIsAddressChanged()
    {
        $address = $this->prophesize('Dizda\Bundle\AppBundle\Entity\Address');
        $addressBlockchain = $this->prophesize('Dizda\Bundle\BlockchainBundle\Model\AddressAbstract');
        $arrayColl = $this->prophesize('Doctrine\Common\Collections\ArrayCollection');


        $address->getValue()->shouldBeCalled()->willReturn('pimpMyAddress');

        $this->watcher->getAddress(Argument::exact('pimpMyAddress'), Argument::exact(false))
            ->shouldBeCalled()
            ->willReturn($addressBlockchain->reveal())
        ;

        $address->getTransactions()
            ->shouldBeCalled()
            ->willReturn($arrayColl->reveal())
        ;

        $addressBlockchain->getTxApperances()->shouldBeCalled()->willReturn(3); // Not equal with count()
        $arrayColl->count()->shouldBeCalled()->willReturn(2);

        $return = $this->provider->isAddressChanged($address->reveal());
        $this->assertInstanceOf('Dizda\Bundle\BlockchainBundle\Model\AddressAbstract', $return);
    }

    /**
     * BlockchainProvider::getBlockchain()
     */
    public function testGetBlockchain()
    {
        $return = $this->provider->getBlockchain();
        $this->assertInstanceOf('Dizda\Bundle\BlockchainBundle\Blockchain\BlockchainWatcherInterface', $return);
    }

    /**
     * Instantiate
     *
     * @before
     */
    public function setUpObjects()
    {
        $this->watcher  = $this->prophesize('Dizda\Bundle\BlockchainBundle\Blockchain\BlockchainWatcherInterface');
        $this->provider = new BlockchainProvider(
            $this->watcher->reveal()
        );
    }
}
