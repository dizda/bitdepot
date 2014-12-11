<?php

namespace Dizda\Bundle\AppBundle\Consumer;

use Dizda\Bundle\AppBundle\Entity\WithdrawOutput;
use Dizda\Bundle\AppBundle\Exception\IncorrectCallbackResponseException;
use Dizda\Bundle\AppBundle\Service\CallbackService;
use Doctrine\ORM\EntityManager;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class WithdrawOutputCallbackConsumer
 */
class WithdrawOutputCallbackConsumer implements ConsumerInterface
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
         * @var int
         */
        $withdrawOutput = $msg->body;

        $withdrawOutput = $this->em->getRepository('DizdaAppBundle:WithdrawOutput')->find($withdrawOutput);

        // call HTTP service callback now
        if (!$this->callbackService->withdrawOutputWithdrawn($withdrawOutput)) {
            throw new IncorrectCallbackResponseException();
        }

        $withdrawOutput->setQueueStatus(WithdrawOutput::QUEUE_STATUS_PROCESSED);
        $this->em->flush();

        return true;
    }
}
