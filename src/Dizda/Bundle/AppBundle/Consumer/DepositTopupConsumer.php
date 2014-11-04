<?php

namespace Dizda\Bundle\AppBundle\Consumer;

use Dizda\Bundle\AppBundle\Entity\DepositTopup;
use Dizda\Bundle\AppBundle\Exception\IncorrectCallbackResponseException;
use Dizda\Bundle\AppBundle\Service\CallbackService;
use Doctrine\ORM\EntityManager;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class DepositTopupConsumer
 */
class DepositTopupConsumer implements ConsumerInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Dizda\Bundle\AppBundle\Service\CallbackService
     */
    private $callbackService;

    /**
     * @param EntityManager   $em
     * @param CallbackService $callbackService
     */
    public function __construct(EntityManager $em, CallbackService $callbackService)
    {
        $this->em = $em;
        $this->callbackService = $callbackService;
    }

    /**
     * @param AMQPMessage $msg
     *
     * @throws IncorrectCallbackResponseException
     * @return bool
     */
    public function execute(AMQPMessage $msg)
    {
        /**
         * @var \Dizda\Bundle\AppBundle\Entity\DepositTopup
         */
        $topup = unserialize($msg->body); // Maybe other thing than serialize php object?

        $topup = $this->em->getRepository('DizdaAppBundle:DepositTopup')->find($topup->getId());

        // call HTTP service callback now
        if (!$this->callbackService->depositTopupFilling($topup)) {
            throw new IncorrectCallbackResponseException();
        }

        $topup->setStatus(DepositTopup::STATUS_PROCESSED);
        $this->em->flush();

        return true;
    }
}
