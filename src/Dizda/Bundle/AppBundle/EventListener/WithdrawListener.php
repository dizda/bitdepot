<?php

namespace Dizda\Bundle\AppBundle\EventListener;

use Dizda\Bundle\AppBundle\Event\WithdrawEvent;
use Dizda\Bundle\AppBundle\Exception\InsufficientAmountException;
use Dizda\Bundle\AppBundle\Manager\AddressManager;
use Nbobtc\Bitcoind\Bitcoind;
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
     * @var \Nbobtc\Bitcoind\Bitcoind
     */
    private $bitcoind;

    /**
     * @var \Dizda\Bundle\AppBundle\Manager\AddressManager
     */
    private $addressManager;

    /**
     * @var \OldSound\RabbitMqBundle\RabbitMq\Producer
     */
    private $withdrawProducer;

    /**
     * @var \OldSound\RabbitMqBundle\RabbitMq\Producer
     */
    private $withdrawOutputProducer;

    /**
     * @param LoggerInterface     $logger
     * @param Bitcoind            $bitcoind
     * @param AddressManager      $addressManager
     * @param Producer            $withdrawOutputProducer
     * @param Producer            $withdrawProducer
     */
    public function __construct(LoggerInterface $logger, Bitcoind $bitcoind, AddressManager $addressManager, Producer $withdrawOutputProducer, Producer $withdrawProducer)
    {
        $this->logger     = $logger;
        $this->bitcoind   = $bitcoind;
        $this->addressManager = $addressManager;
        $this->withdrawProducer = $withdrawProducer;
        $this->withdrawOutputProducer = $withdrawOutputProducer;
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

        // Let bitcoind to create the rawtransaction
        $rawTransaction = $this->bitcoind->createrawtransaction(
            $withdraw->getWithdrawInputsSerializable(),
            $withdraw->getWithdrawOutputsSerializable()
        );

        $withdraw->setRawTransaction($rawTransaction);
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

            foreach ($input->getAddresses() as $address) {
                $address->setBalance(0);
            }
        }

        // Dispatch every outputs to rabbit, to launch a callback to all of them
        foreach ($withdraw->getWithdrawOutputs() as $output) {
            $this->withdrawOutputProducer->publish($output->getId());
        }

//        $this->withdrawProducer->publish(serialize([
//            'txid' => $transactionId
//        ]));
    }
}
