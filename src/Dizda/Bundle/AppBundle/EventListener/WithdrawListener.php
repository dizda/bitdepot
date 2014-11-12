<?php

namespace Dizda\Bundle\AppBundle\EventListener;

use Dizda\Bundle\AppBundle\Event\WithdrawEvent;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
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
     * @var \JMS\Serializer\SerializerInterface
     */
    private $serializer;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger, SerializerInterface $serializer)
    {
        $this->logger     = $logger;
        $this->serializer = $serializer;
    }

    /**
     * @param WithdrawEvent $event
     */
    public function onCreate(WithdrawEvent $event)
    {
        $withdraw = $event->getWithdraw();

        $contextInput = new SerializationContext();
        $groups[] = 'Withdraw';
        $contextInput->setGroups($groups);

        $contextOutput = new SerializationContext();
        $groups[] = 'Withdraw';
        $contextOutput->setGroups($groups);

        $inputs = $this->serializer->serialize($withdraw->getWithdrawInputs(), 'json', $contextInput);
        $outputs = $this->serializer->serialize($withdraw->getWithdrawOutputsSerialized(), 'json', $contextOutput);


        // call bitcoind here
    }

}
