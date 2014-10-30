<?php

namespace Dizda\Bundle\BlockchainBundle\Manager;

use Dizda\Bundle\BlockchainBundle\Blockchain\BlockchainProvider;
use Doctrine\ORM\EntityManager;

class BlockchainManager
{
    private $provider;
    private $em;

    public function __construct(BlockchainProvider $provider, EntityManager $em)
    {
        $this->provider = $provider;
        $this->em       = $em;
    }

    public function update()
    {
        $deposits = $this->em->getRepository('DizdaAppBundle:Deposit')->getOpenDeposits();

        foreach ($deposits as $deposit) {
            if (!$this->provider->isDepositChanged($deposit)) {
                continue;
            }

            //dispatch
            $this->provider->getBlockchain()->getTransactionsByAddress()
        }


    }

}
