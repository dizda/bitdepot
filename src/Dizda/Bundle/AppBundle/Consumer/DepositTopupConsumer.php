<?php

namespace Dizda\Bundle\AppBundle\Consumer;

use Dizda\Bundle\AppBundle\Entity\DepositTopup;
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
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param AMQPMessage $msg
     *
     * @return bool
     */
    public function execute(AMQPMessage $msg)
    {
        /**
         * @var \Dizda\Bundle\AppBundle\Entity\DepositTopup
         */
        $topup = unserialize($msg->body);

        $topup->setStatus(DepositTopup::STATUS_PROCESSING);

        $this->em->merge($topup); // reattach entity
        $this->em->flush();

        // call HTTP service callback now

        return true;
    }
}
