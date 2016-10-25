<?php

namespace Dizda\Bundle\AppBundle\Manager;

use Dizda\Bundle\AppBundle\Entity\Deposit;
use Dizda\Bundle\AppBundle\Service\HttpService;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
     * @var AddressManager
     */
    private $addressManager;

    /**
     * @var HttpService
     */
    private $httpService;

    /**
     * @param EntityManager            $em
     * @param LoggerInterface          $logger
     * @param EventDispatcherInterface $dispatcher
     * @param AddressManager           $addressManager
     * @param HttpService              $httpService
     */
    public function __construct(EntityManager $em, LoggerInterface $logger, EventDispatcherInterface $dispatcher, AddressManager $addressManager, HttpService $httpService)
    {
        $this->em         = $em;
        $this->logger     = $logger;
        $this->dispatcher = $dispatcher;
        $this->addressManager = $addressManager;
        $this->httpService = $httpService;
    }

    /**
     * @param array $depositSubmitted Submitted in JSON
     *
     * @return Deposit
     */
    public function create(array $depositSubmitted)
    {
        $app = $this->em->getRepository('DizdaAppBundle:Application')->find($depositSubmitted['application_id']);

        if (isset($depositSubmitted['amount_expected_fiat'])) {
            $depositSubmitted = $this->calculateAmountIfFiatPrice($depositSubmitted);
        }

        // Generate an external address
        $address = $this->addressManager->create($app, true);

        $deposit = (new Deposit())
            ->setType($depositSubmitted['type'])
            ->setApplication($app) // TODO: verify that application id is owned by user
            ->setAddressExternal($address)
            ->setReference($depositSubmitted['reference'])
            ->setExpiresAt(new \DateTime($app->getDepositsTopupsExpiresAfter()))
        ;

        if ($depositSubmitted['type'] === 1) {
            $deposit
                ->setAmountExpected($depositSubmitted['amount_expected'])
                ->setExpiresAt(new \DateTime($app->getDepositsExpiresAfter()))
            ;
        }

        $this->em->persist($deposit);

        return $deposit;
    }

    /**
     * @param array $depositSubmitted
     *
     * @return array
     * @throws \Exception
     */
    public function calculateAmountIfFiatPrice(array $depositSubmitted)
    {
        // If the amount has been provided in FIAT currency, get the amount in BTC with the help of blockchain.info
        $response = $this->httpService->get('https://blockchain.info/ticker'); // no need to cache it for now

        if ($response->getStatusCode() !== 200) {
            $this->logger->critical('Can not get the price from Blockchain.info');

            throw new \Exception('Blockchain.info is not reachable');
        }

        $json = $response->json();
        $currency = strtoupper($depositSubmitted['amount_expected_fiat']['currency']);

        if (!isset($json[$currency])) {
            throw new \Exception(sprintf('The currency %s is not supported.', $currency));
        }

        $depositSubmitted['amount_expected'] = bcdiv((string) ((int) $depositSubmitted['amount_expected_fiat']['amount'] / 100), (string) $json[$currency]['15m'], 8);

        return $depositSubmitted;
    }
}
