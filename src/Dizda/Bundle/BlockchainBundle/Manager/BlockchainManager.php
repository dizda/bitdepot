<?php

namespace Dizda\Bundle\BlockchainBundle\Manager;

use Dizda\Bundle\AppBundle\AppEvents;
use Dizda\Bundle\AppBundle\Event\DepositEvent;
use Dizda\Bundle\AppBundle\Manager\AddressManager;
use Dizda\Bundle\BlockchainBundle\Blockchain\BlockchainProvider;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class BlockchainManager
 */
class BlockchainManager
{
    /**
     * @var \Dizda\Bundle\BlockchainBundle\Blockchain\BlockchainProvider
     */
    private $provider;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Dizda\Bundle\AppBundle\Manager\AddressManager
     */
    private $addressManager;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param BlockchainProvider       $provider
     * @param EntityManager            $em
     * @param AddressManager           $addressManager
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(BlockchainProvider $provider, EntityManager $em, AddressManager $addressManager, EventDispatcherInterface $dispatcher)
    {
        $this->provider       = $provider;
        $this->em             = $em;
        $this->addressManager = $addressManager;
        $this->dispatcher     = $dispatcher;
    }

    /**
     * Update deposits
     */
    public function update()
    {
        $deposits = $this->em->getRepository('DizdaAppBundle:Deposit')->getOpenDeposits();

        foreach ($deposits as $deposit) {
            if (!$address = $this->provider->isAddressChanged($deposit->getAddressExternal())) {
                continue;
            }

            // Get transactions
            $transactions = $this->provider->getBlockchain()->getTransactionsByAddress(
                $deposit->getAddressExternal()->getValue(),
                $deposit->getApplication()->getConfirmationsRequired()
            );

            // We reduce transactions along required confirmations, so we have to check again before continue
            if (!count($transactions)) {
                continue;
            }

            // Save them
            $this->addressManager->saveTransactions($deposit->getAddressExternal(), $transactions);

            // Update balance
            $deposit->getAddressExternal()->setBalance($address->getBalance());

            $this->dispatcher->dispatch(AppEvents::DEPOSIT_UPDATED, new DepositEvent($deposit));
        }

        $this->em->flush();
    }

}
