<?php

namespace Dizda\Bundle\AppBundle\Service;

use Dizda\Bundle\AppBundle\Entity\Deposit;
use Dizda\Bundle\AppBundle\Entity\DepositTopup;
use GuzzleHttp\Client;
use JMS\Serializer\SerializerInterface;

/**
 * Class CallbackService
 *
 * Callback the service API when a transaction is received.
 */
class CallbackService
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @var \JMS\Serializer\SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->client     = new Client();
    }

    public function depositExpectedFilling(Deposit $deposit)
    {
        $baseUrl = $deposit->getApplication()->getCallbackEndpoint();

        $response = $this->client->post(sprintf('%s/deposit.json', $baseUrl), [
            'json' => $this->serializer->serialize($deposit, 'json')
        ]);

        return (int) $response->getStatusCode() === 200;
    }

    /**
     * @param DepositTopup $depositTopup
     *
     * @return bool
     */
    public function depositTopupFilling(DepositTopup $depositTopup)
    {
        $baseUrl = $depositTopup->getDeposit()->getApplication()->getCallbackEndpoint();

        $response = $this->client->post(sprintf('%s/topup.json', $baseUrl), [
            'json' => $this->serializer->serialize($depositTopup, 'json')
        ]);

        return (int) $response->getStatusCode() === 201;
    }
}
