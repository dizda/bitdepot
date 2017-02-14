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
     * @param WithdrawEvent $event
     *
     * @throws InsufficientAmountException
     * @deprecated Use {@link WithdrawListener::onCreate()}
     * @codeCoverageIgnore
     */
    public function onCreateWithBitcoind(WithdrawEvent $event)
    {
        $withdraw = $event->getWithdraw();

        $withdraw->setChangeAddressAmount(bcsub($withdraw->getTotalInputs(), $withdraw->getTotalOutputsWithFees(), 8));

        if (!$withdraw->isSpendable()) {
            throw new InsufficientAmountException();
        }

        // $withdraw->getChangeAddressAmount() > 0
        if (bccomp($withdraw->getChangeAddressAmount(), '0', 8) === 1) {
            // Get a changeaddress
            $changeAddress = $this->addressManager->create($withdraw->getWithdrawOutputs()[0]->getApplication(), false);

            // Sending change to a changeaddress
            $withdraw->setChangeAddress($changeAddress);
        }

        // Let bitcoind to create the rawtransaction
        $rawTransaction = $this->bitcoind->createrawtransaction(
            $withdraw->getWithdrawInputsSerializable(),
            $withdraw->getWithdrawOutputsSerializable()
        );

        $withdraw->setRawTransaction($rawTransaction);
    }

    /**
     * @param WithdrawEvent $event
     *
     * @throws InsufficientAmountException
     */
    public function onCreate(WithdrawEvent $event)
    {
        $withdraw = $event->getWithdraw();

        $withdraw->setChangeAddressAmount(bcsub($withdraw->getTotalInputs(), $withdraw->getTotalOutputsWithFees(), 8));

        if (!$withdraw->isSpendable()) {
            throw new InsufficientAmountException();
        }

        // $withdraw->getChangeAddressAmount() > 0
        if (bccomp($withdraw->getChangeAddressAmount(), '0', 8) === 1) {
            // Get a changeaddress
            $changeAddress = $this->addressManager->create($withdraw->getWithdrawOutputs()[0]->getApplication(), false);

            // Sending change to a changeaddress
            $withdraw->setChangeAddress($changeAddress);
        }

        // prefer to send it via RabbitMQ due to terminal chars limit
        $transaction = $this->bitcoreService->buildTransaction(
            $withdraw->getWithdrawInputs(),
            $withdraw->getWithdrawOutputs(),
            $withdraw->getChangeAddress()
        );

        // during the creation of the transaction, bitcore can propose a better fee to cover all outputs
        $withdraw->setFees($transaction['fees']);
        // so we have to update the change amount who'll be impacted as well
        $withdraw->setChangeAddressAmount($transaction['change_amount']);
        $withdraw->setRawTransaction($transaction['raw_transaction']);
        $withdraw->setJsonTransaction(json_encode($transaction['json_transaction']));

        if (!$withdraw->isSpendable()) {
            throw new InsufficientAmountException();
        }
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
