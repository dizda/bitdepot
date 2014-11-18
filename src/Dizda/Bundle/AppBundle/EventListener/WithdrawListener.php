<?php

namespace Dizda\Bundle\AppBundle\EventListener;

use Dizda\Bundle\AppBundle\Event\WithdrawEvent;
use Nbobtc\Bitcoind\Bitcoind;
use Psr\Log\LoggerInterface;

/**
 * Class WithdrawListener
 */
class WithdrawListener
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Nbobtc\Bitcoind\Bitcoind
     */
    private $bitcoind;

    /**
     * @param LoggerInterface     $logger
     * @param Bitcoind            $bitcoind
     */
    public function __construct(LoggerInterface $logger, Bitcoind $bitcoind)
    {
        $this->logger     = $logger;
        $this->bitcoind   = $bitcoind;
    }

    /**
     * @param WithdrawEvent $event
     */
    public function onCreate(WithdrawEvent $event)
    {
        $withdraw = $event->getWithdraw();

        $toChangeAddress = bcsub($withdraw->getTotalInputs(), $withdraw->getTotalOutputsWithFees(), 8);

        // Let bitcoind to create the rawtransaction
        $rawTransaction = $this->bitcoind->createrawtransaction(
            $withdraw->getWithdrawInputsSerializable(),
            $withdraw->getWithdrawOutputsSerializable()
        );

        $withdraw->setRawTransaction($rawTransaction);
        $withdraw->setAmountTransferredToChange($toChangeAddress);
    }

    /**
     * When the withdraw raw hex is sended to the blockchain
     *
     * @param WithdrawEvent $event
     */
    public function onSend(WithdrawEvent $event)
    {
        $withdraw = $event->getWithdraw();

        $transactionId = $this->bitcoind->sendrawtransaction(
            $withdraw->getRawSignedTransaction()
        );

        $withdraw->withdrawed($transactionId);

        // Mark all inputs as spent, and reset the balance of each related addresses
        foreach ($withdraw->getWithdrawInputs() as $input) {
            $input->setIsSpent(true);
            $input->getAddress()->setBalance(0);
        }

        // Dispatch an event here, to launch a callback to all outputs
    }
}
