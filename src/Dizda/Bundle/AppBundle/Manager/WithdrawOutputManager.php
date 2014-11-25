<?php

namespace Dizda\Bundle\AppBundle\Manager;

use Dizda\Bundle\AppBundle\Entity\WithdrawOutput;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class WithdrawOutputManager
 */
class WithdrawOutputManager
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

    /**
     * @param array $withdrawOutputSubmitted Submitted in JSON
     *
     * @return WithdrawOutput
     */
    public function create(array $withdrawOutputSubmitted)
    {
        $app = $this->em->getRepository('DizdaAppBundle:Application')->find($withdrawOutputSubmitted['application_id']);

        $withdrawOutput = (new WithdrawOutput())
            ->setAmount($withdrawOutputSubmitted['amount'])
            ->setToAddress($withdrawOutputSubmitted['to_address'])
            ->setIsAccepted($withdrawOutputSubmitted['is_accepted'])
            ->setReference($withdrawOutputSubmitted['reference'])
            ->setApplication($app) // TODO: verify that application id is owned by user
        ;

        $this->em->persist($withdrawOutput);

        return $withdrawOutput;
    }

}
