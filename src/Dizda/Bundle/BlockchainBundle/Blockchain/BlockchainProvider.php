<?php

namespace Dizda\Bundle\BlockchainBundle\Blockchain;
use Dizda\Bundle\AppBundle\Entity\Address;

/**
 * Class BlockchainProvider
 */
class BlockchainProvider
{
    /**
     * @var BlockchainWatcherInterface
     */
    private $watcher;

    /**
     * @param BlockchainWatcherInterface $watcher
     */
    public function __construct(BlockchainWatcherInterface $watcher)
    {
        $this->watcher = $watcher;
    }

    /**
     * @return BlockchainWatcherInterface
     */
    public function getBlockchain()
    {
        return $this->watcher;
    }

    public function isAddressChanged(Address $address)
    {
        $addressFromBlockchain = $this->watcher->getAddress($address->getValue(), false);

        if ($address->getTransactions()->count() === $addressFromBlockchain->getTxApperances()) {
            return false;
        }

        return true;
    }

}
