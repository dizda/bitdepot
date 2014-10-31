<?php

namespace Dizda\Bundle\BlockchainBundle\Manager;

use Dizda\Bundle\AppBundle\Entity\AddressTransaction;
use Dizda\Bundle\AppBundle\Manager\AddressManager;
use Dizda\Bundle\BlockchainBundle\Blockchain\BlockchainProvider;
use Doctrine\ORM\EntityManager;

class BlockchainManager
{
    private $provider;
    private $em;
    private $addressManager;

    public function __construct(BlockchainProvider $provider, EntityManager $em, AddressManager $addressManager)
    {
        $this->provider       = $provider;
        $this->em             = $em;
        $this->addressManager = $addressManager;
    }

    public function update()
    {
        $deposits = $this->em->getRepository('DizdaAppBundle:Deposit')->getOpenDeposits();

        foreach ($deposits as $deposit) {
            if (!$this->provider->isAddressChanged($deposit->getAddressExternal())) {
                continue;
            }

            // Get transactions
            $transactions = $this->provider->getBlockchain()
                ->getTransactionsByAddress($deposit->getAddressExternal()->getValue())
            ;

            // Save them
            $this->addressManager->saveTransactions($deposit->getAddressExternal(), $transactions);

            //dispatch
        }

        $this->em->flush();
    }

}
