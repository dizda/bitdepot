<?php

namespace Dizda\Bundle\AppBundle\EventListener;

use Dizda\Bundle\AppBundle\Entity\Withdraw;
use Dizda\Bundle\AppBundle\Entity\WithdrawOutput;
use Doctrine\ORM\Event\LifecycleEventArgs;
use OldSound\RabbitMqBundle\RabbitMq\Producer;

/**
 * Class DepositEntityListener
 *
 * Trigger action when a withdraw occurs
 */
class WithdrawEntityListener
{
    /**
     * @var \OldSound\RabbitMqBundle\RabbitMq\Producer
     */
    private $withdrawOutputProducer;

    /**
     * @param Producer $withdrawOutputProducer
     */
    public function __construct(Producer $withdrawOutputProducer)
    {
        $this->withdrawOutputProducer = $withdrawOutputProducer;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // When a deposit is occurred, we verify that is not a Topup reload
        if ($entity instanceof Withdraw) {
            // If RabbitMQ is not available, an Exception will be thrown, and a rollback of transactions will be made
            // so we will not loose any topup transactions if an error occur.
            if ($entity->getWithdrawedAt() === null) {
                return;
            }

            // If the withdraw was already processed, we don't push it a second time
            if ($entity->getWithdrawOutputs()->first()->getQueueStatus() !== WithdrawOutput::QUEUE_STATUS_QUEUED) {
                return;
            }

            // Dispatch every outputs related to the withdraw to rabbit, to launch a callback to all of them
            foreach ($entity->getWithdrawOutputs() as $output) {
                if ($output->getReference()) {
                    $this->withdrawOutputProducer->publish(serialize($output->getId()));
                }
            }
        }
    }

}
