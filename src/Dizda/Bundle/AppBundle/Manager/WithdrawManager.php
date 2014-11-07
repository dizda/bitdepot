<?php

namespace Dizda\Bundle\AppBundle\Manager;

use Dizda\Bundle\AppBundle\Entity\Application;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class WithdrawManager
 */
class WithdrawManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param EntityManager            $em
     * @param LoggerInterface          $logger
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EntityManager $em, LoggerInterface $logger, EventDispatcherInterface $dispatcher)
    {
        $this->em         = $em;
        $this->logger     = $logger;
        $this->dispatcher = $dispatcher;
    }

    public function search(Application $application)
    {
        $outputs = $this->em->getRepository('DizdaAppBundle:WithdrawOutput')->getWhereWithdrawIsNull($application);

        if ($application->getGroupWithdrawsByQuantity()
            || $application->getGroupWithdrawsByQuantity() < count($outputs)) {

            return false;
        }



    }

}
