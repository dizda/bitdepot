<?php

namespace Dizda\Bundle\AppBundle\Manager;

use Dizda\Bundle\AppBundle\Entity\Address;
use Dizda\Bundle\AppBundle\Entity\Deposit;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\ORM\NoResultException;

/**
 * Class DepositManager
 */
class DepositManager
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
     * @param array $depositSubmitted Submitted in JSON
     *
     * @return Deposit
     */
    public function create(array $depositSubmitted)
    {
        $app = $this->em->getRepository('DizdaAppBundle:Application')->find($depositSubmitted['application_id']);

        // Find an address not used yet.
        try {
            $address = $this->em->getRepository('DizdaAppBundle:Address')->getOneFreeAddress();
        } catch (NoResultException $e) {
            $this->logger->alert('There is no available address anymore! Please generate new addresses.');

            throw new NoResultException();
        }

        $deposit = new Deposit();
        $deposit
            ->setType($depositSubmitted['type'])
            ->setApplication($app) // TODO: verify that application id is owned by user
            ->setAddressExternal($address)
        ;

        if ($depositSubmitted['type'] === 1) {
            $deposit->setAmountExpected($depositSubmitted['amount_expected']);
        }

        $this->em->persist($deposit);

        return $deposit;
    }

}
