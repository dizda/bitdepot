<?php

namespace Dizda\Bundle\BlockchainBundle\Blockchain;

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

}