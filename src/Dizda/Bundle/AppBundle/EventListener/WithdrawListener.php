<?php

namespace Dizda\Bundle\AppBundle\EventListener;

use Dizda\Bundle\AppBundle\Event\WithdrawEvent;
use Dizda\Bundle\AppBundle\Exception\InsufficientAmountException;
use Dizda\Bundle\AppBundle\Manager\AddressManager;
use Dizda\Bundle\AppBundle\Service\BitcoreService;
use Dizda\Bundle\AppBundle\Entity\WithdrawOutput;
use Psr\Log\LoggerInterface;
use OldSound\RabbitMqBundle\RabbitMq\Producer;

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
     * @var \Dizda\Bundle\AppBundle\Manager\AddressManager
     */
    private $addressManager;

    /**
     * @var BitcoreService
     */
    private $bitcoreService;

    /**
     * @var \OldSound\RabbitMqBundle\RabbitMq\Producer
     */
    private $withdrawProducer;

    /**
     * @param LoggerInterface           $logger
     * @param AddressManager            $addressManager
     * @param BitcoreService            $bitcoreService
     * @param Producer                  $withdrawProducer
     */
    public function __construct(LoggerInterface $logger, AddressManager $addressManager, BitcoreService $bitcoreService, Producer $withdrawProducer)
    {
        $this->logger     = $logger;
        $this->addressManager = $addressManager;
        $this->bitcoreService = $bitcoreService;
        $this->withdrawProducer       = $withdrawProducer;
    }

    /**
     * When the withdraw raw hex is sended to the blockchain
     *
     * @param WithdrawEvent $event
     */
    public function onSend(WithdrawEvent $event)
    {
        $withdraw = $event->getWithdraw();

//        $transactionId = $this->bitcoind->sendrawtransaction(
//            $withdraw->getRawSignedTransaction()
//        );
        $transactionId = $this->bitcoreService->broadcastTransaction($withdraw->getRawSignedTransaction());

        $withdraw->withdrawed($transactionId);

        // Mark all inputs as spent, and reset the balance of each related addresses
        foreach ($withdraw->getWithdrawInputs() as $input) {
            $input->setIsSpent(true);
            $input->getAddress()->setBalance(0);
        }

        // On flush(), WithdrawEntityListener will be triggered to send all withdrawOutputs to RabbitMQ
        foreach ($withdraw->getWithdrawOutputs() as $output) {
            $output->setQueueStatus(WithdrawOutput::QUEUE_STATUS_QUEUED);
        }
    }
}
