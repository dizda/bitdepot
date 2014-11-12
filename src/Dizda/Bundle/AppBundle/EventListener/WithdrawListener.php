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

        // Let bitcoind to create the rawtransaction
        $rawTransaction = $this->bitcoind->createrawtransaction(
            $withdraw->getWithdrawInputsSerializable(),
            $withdraw->getWithdrawOutputsSerializable()
        );

        $withdraw->setRawTransaction($rawTransaction);
    }

}
