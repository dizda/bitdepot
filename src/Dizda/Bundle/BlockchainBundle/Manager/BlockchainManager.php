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
     * Watch deposit & change addresses
     */
    public function monitor()
    {
        $this->monitorOpenDeposits();
        $this->monitorChangeAddresses();

        $this->em->flush();
    }

    /**
     * Watch deposit addresses
     */
    public function monitorOpenDeposits()
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
            $transactionsIncoming = $this->addressManager->saveTransactions($deposit->getAddressExternal(), $transactions);

            if (!count($transactionsIncoming)) {
                continue;
            }

            // Update balance
            $deposit->getAddressExternal()->setBalance($address->getBalance());

            $this->dispatcher->dispatch(AppEvents::DEPOSIT_UPDATED, new DepositEvent($deposit, $transactionsIncoming));
        }
    }

    /**
     * Watch change addresses
     */
    public function monitorChangeAddresses()
    {
        $withdraws = $this->em->getRepository('DizdaAppBundle:Withdraw')->getChangeAddressesMonitored();

        foreach ($withdraws as $withdraw) {
            if (!$address = $this->provider->isAddressChanged($withdraw->getChangeAddress())) {
                continue;
            }

            // Get transactions
            $transactions = $this->provider->getBlockchain()->getTransactionsByAddress(
                $withdraw->getChangeAddress()->getValue(),
                1 // TODO: Set an application id into Address in order to get the application from here
            );

            // We reduce transactions along required confirmations, so we have to check again before continue
            if (!count($transactions)) {
                continue;
            }

            // Save them
            $transactionsIncoming = $this->addressManager->saveTransactions($withdraw->getChangeAddress(), $transactions);

            // Update balance
            $withdraw->getChangeAddress()->setBalance($address->getBalance());

            // TODO: Dispatch a CHANGE EVENT
            //$this->dispatcher->dispatch(AppEvents::DEPOSIT_UPDATED, new DepositEvent($deposit, $transactionsIncoming));
        }
    }

}
