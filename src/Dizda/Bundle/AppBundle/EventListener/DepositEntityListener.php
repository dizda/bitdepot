<?php

namespace Dizda\Bundle\AppBundle\EventListener;

use Dizda\Bundle\AppBundle\Entity\Deposit;
use Doctrine\ORM\Event\LifecycleEventArgs;
use OldSound\RabbitMqBundle\RabbitMq\Producer;

/**
 * Class DepositEntityListener
 */
class DepositEntityListener
{
    /**
     * @var \OldSound\RabbitMqBundle\RabbitMq\Producer
     */
    private $depositProducer;

    /**
     * @param Producer $depositProducer
     */
    public function __construct(Producer $depositProducer)
    {
        $this->depositProducer = $depositProducer;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        // When a deposit is occurred, we verify that is not a Topup reload
        if ($entity instanceof Deposit) {
            // If RabbitMQ is not available, an Exception will be thrown, and a rollback of transactions will be made
            // so we will not loose any topup transactions if an error occur.
            if ($entity->getType() !== Deposit::TYPE_AMOUNT_EXPECTED) {
                return;
            }

            $this->depositProducer->publish(serialize($entity));
        }
    }

}
