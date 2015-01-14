<?php

namespace Dizda\Bundle\AppBundle\Service;

use Dizda\Bundle\AppBundle\Entity\Deposit;
use Dizda\Bundle\AppBundle\Entity\DepositTopup;
use Dizda\Bundle\AppBundle\Entity\WithdrawOutput;
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
     * @var \JMS\Serializer\SerializerInterface
     */
    private $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param $baseUrl
     *
     * @return Client
     */
    public function initialize($baseUrl)
    {
        return new Client(['base_url' => $baseUrl]);
    }

    /**
     * @param Deposit $deposit
     *
     * @return bool
     */
    public function depositExpectedFilling(Deposit $deposit)
    {
        $client = $this->initialize($deposit->getApplication()->getCallbackEndpoint());

        dump($this->serializer->serialize($deposit, 'json'));
        $response = $client->post('deposit.json', [
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
        $client = $this->initialize($depositTopup->getDeposit()->getApplication()->getCallbackEndpoint());

        $response = $client->post('topup.json', [
            'json' => $this->serializer->serialize($depositTopup, 'json')
        ]);

        return (int) $response->getStatusCode() === 201;
    }

    /**
     * Notify the application when an output got withdrawn
     *
     * @param WithdrawOutput $withdrawOutput
     *
     * @return bool
     */
    public function withdrawOutputWithdrawn(WithdrawOutput $withdrawOutput)
    {
        $client = $this->initialize($withdrawOutput->getApplication()->getCallbackEndpoint());

        $response = $client->post('withdraw_output.json', [
            'json' => $this->serializer->serialize($withdrawOutput, 'json')
        ]);

        return (int) $response->getStatusCode() === 200;
    }
}
