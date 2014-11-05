<?php

namespace Dizda\Bundle\AppBundle\Consumer;

use Dizda\Bundle\AppBundle\Entity\DepositTopup;
use Dizda\Bundle\AppBundle\Exception\IncorrectCallbackResponseException;
use Dizda\Bundle\AppBundle\Service\CallbackService;
use Doctrine\ORM\EntityManager;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class DepositConsumer
 */
class DepositConsumer implements ConsumerInterface
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
         * @var \Dizda\Bundle\AppBundle\Entity\Deposit
         */
        $deposit = unserialize($msg->body); // Maybe other thing than serialize php object?

        $deposit = $this->em->getRepository('DizdaAppBundle:Deposit')->find($deposit->getId());

        // call HTTP service callback now
        if (!$this->callbackService->depositExpectedFilling($deposit)) {
            throw new IncorrectCallbackResponseException();
        }

        //TODO: add STATUS_QUEUED, STATUS_PROCESSED, etc to deposit?
        $deposit->setStatus(DepositTopup::STATUS_PROCESSED);
        $this->em->flush();

        return true;
    }
}
