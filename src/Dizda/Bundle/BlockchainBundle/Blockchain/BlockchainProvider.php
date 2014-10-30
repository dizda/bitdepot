<?php

namespace Dizda\Bundle\BlockchainBundle\Blockchain;
use Dizda\Bundle\AppBundle\Entity\Deposit;

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

    public function isDepositChanged(Deposit $deposit)
    {
        $addressFromBlockchain = $this->watcher->getAddress($deposit->getAddressExternal()->getValue(), false);

        if ($deposit->getTransactions()->count() === $addressFromBlockchain->getTxApperances()) {
            return false;
        }

        return true;
    }

}
