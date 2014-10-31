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
            if (!$this->provider->isAddressChanged($deposit->getAddressExternal())) {
                continue;
            }

            var_dump($deposit->getAddressExternal()->getValue());

            $transactions = $this->provider->getBlockchain()
                ->getTransactionsByAddress($deposit->getAddressExternal()->getValue())
            ;

            foreach ($transactions as $transaction) {
                var_dump($transaction->getTxid());
                if ($deposit->getAddressExternal()->hasTransaction($transaction->getTxid())) {
                    continue;
                }

                var_dump('not exist, need to be persisted there');
                // add the transaction there
            }
//            var_dump($this->provider->getBlockchain()->getTransactionsByAddress($deposit->getAddressExternal()->getValue())->getTxs()[0]->getOutputs());
            //dispatch
//            $this->provider->getBlockchain()->getTransactionsByAddress()
        }


    }

}
