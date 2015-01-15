<?php

namespace Dizda\Bundle\AppBundle\Consumer;

use Dizda\Bundle\AppBundle\Exception\IncorrectCallbackResponseException;
use Dizda\Bundle\AppBundle\Service\CallbackService;
use Dizda\Bundle\AppBundle\Traits\MessageQueuingInterface;
use Doctrine\ORM\EntityManager;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Dispatch correct callback to the application
 *
 * Class CallbackConsumer
 */
class CallbackConsumer implements ConsumerInterface
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
     *
     * @return bool
     */
    public function execute(AMQPMessage $msg)
    {
        /**
         * @var integer
         */
        $entityId = unserialize($msg->body);

        $entity = $this->em->getRepository($this->guessRepository($msg->delivery_info['exchange']))->find($entityId);
        $method = $this->guessServiceMethod($msg->delivery_info['exchange']);

        // call HTTP service callback now
        if (!$this->callbackService->$method($entity)) {
            // TODO: add a logger there

            throw new IncorrectCallbackResponseException();
        }

        $entity->setQueueStatus(MessageQueuingInterface::QUEUE_STATUS_PROCESSED);

        $this->em->flush();

        return true;
    }

    /**
     * Return appropriate repository according to the exchange type
     *
     * @param string $exchange
     *
     * @return string
     */
    private function guessRepository($exchange)
    {
        switch ($exchange) {
            case 'deposit-callback':
                return 'DizdaAppBundle:Deposit';
                break;
            case 'deposit-topup-callback':
                return 'DizdaAppBundle:DepositTopup';
                break;
            case 'withdraw-output-callback':
                return 'DizdaAppBundle:WithdrawOutput';
                break;
        }

        return false;
    }

    /**
     * @param string $exchange
     *
     * @return string
     */
    private function guessServiceMethod($exchange)
    {
        switch ($exchange) {
            case 'deposit-callback':
                return 'depositExpectedFilling';
                break;
            case 'deposit-topup-callback':
                return 'depositTopupFilling';
                break;
            case 'withdraw-output-callback':
                return 'withdrawOutputWithdrawn';
                break;
        }

        return false;
    }
}
