<?php

namespace Dizda\Bundle\AppBundle\Service;

use Dizda\Bundle\AppBundle\Entity\Deposit;
use Dizda\Bundle\AppBundle\Entity\DepositTopup;
use Dizda\Bundle\AppBundle\Entity\WithdrawOutput;
use GuzzleHttp\Client;
use JMS\Serializer\SerializationContext;
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
     * @var HttpService
     */
    private $http;

    /**
     * @param SerializerInterface $serializer
     * @param HttpService         $http
     */
    public function __construct(SerializerInterface $serializer, HttpService $http)
    {
        $this->serializer = $serializer;
        $this->http       = $http;
    }

    /**
     * @param Deposit $deposit
     *
     * @return bool
     */
    public function depositExpectedFilling(Deposit $deposit)
    {
        sleep(1);

        $url = sprintf('%s/callback/deposit/expected.json', $deposit->getApplication()->getCallbackEndpoint());

        $response = $this->http->post($url, [
            'body'    => $this->serialize($deposit, 'DepositCallback'),
            'headers' => [
                'content-type' => 'application/json'
            ]
        ]); // We switch to json content manually, because we serialize ourselves

        return (int) $response->getStatusCode() === 200;
    }

    /**
     * @param DepositTopup $depositTopup
     * TODO: !!
     *
     * @return bool
     * @deprecated TO be updated
     */
    public function depositTopupFilling(DepositTopup $depositTopup)
    {
//        $client = $this->initialize($depositTopup->getDeposit()->getApplication()->getCallbackEndpoint());
//
//        $response = $client->post('topup.json', [
//            'json' => $this->serializer->serialize($depositTopup, 'json')
//        ]);
//
//        return (int) $response->getStatusCode() === 201;
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
        $url = sprintf('%s/callback/withdraw/output.json', $withdrawOutput->getApplication()->getCallbackEndpoint());

        $response = $this->http->post($url, [
            'body' => $this->serialize($withdrawOutput, 'WithdrawOutputCallback'),
            'headers' => [
                'content-type' => 'application/json'
            ]
        ]);

        return (int) $response->getStatusCode() === 200;
    }

    /**
     * @param \stdClass $subject Object to serialize
     * @param string    $group   The JMS\Groups
     *
     * @return string
     */
    private function serialize($subject, $group)
    {
        $context = (new SerializationContext())
            ->setGroups($group)
        ;

        return $this->serializer->serialize($subject, 'json', $context);
    }
}
