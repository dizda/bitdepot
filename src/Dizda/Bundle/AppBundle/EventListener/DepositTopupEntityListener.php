<?php

namespace Dizda\Bundle\AppBundle\EventListener;

use Dizda\Bundle\AppBundle\Entity\DepositTopup;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\PostPersist;
use OldSound\RabbitMqBundle\RabbitMq\Producer;

/**
 * Class DepositTopupEntityListener
 */
class DepositTopupEntityListener
{
    /**
     * @var \OldSound\RabbitMqBundle\RabbitMq\Producer
     */
    private $topupProducer;

    /**
     * @param Producer $topupProducer
     */
    public function __construct(Producer $topupProducer)
    {
        $this->topupProducer = $topupProducer;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        //$entityManager = $args->getEntityManager();

        // Waiting to $topup to get his $id, to push him to rabbitmq
        if ($entity instanceof DepositTopup) {
            // If RabbitMQ is not available, an Exception will be thrown, and a rollback of transactions will be made
            // so we will not loose any topup transactions if an error occur.
            $this->topupProducer->publish(serialize($entity->getId()));
        }
    }

}
