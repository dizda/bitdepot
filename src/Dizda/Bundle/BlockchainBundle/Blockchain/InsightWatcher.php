<?php

namespace Dizda\Bundle\BlockchainBundle\Blockchain;

use Dizda\Bundle\BlockchainBundle\Client\HttpClient;
use JMS\Serializer\SerializerInterface;

class InsightWatcher extends BlockchainBase implements BlockchainWatcherInterface
{
    /**
     * @var \Dizda\Bundle\BlockchainBundle\Client\HttpClient
     */
    private $client;

    public function __construct(HttpClient $http, SerializerInterface $serializer)
    {
        $this->client = $http->getClient();
        $this->serializer = $serializer;
    }

    public function getAddress($address, $withTransactions)
    {
        $response = $this->client->get('addr/3C2E7n7QsoaogcqynCumfJrpotKFYNwgR4');

        return $this->serializer->deserialize($response->getBody(), 'Dizda\Bundle\BlockchainBundle\Model\Insight\Address', 'json');
    }

    public function getAddresses(array $addresses, $withTransactions)
    {
        throw new \Exception('Not implemented.');
    }

    public function getTransaction($txid)
    {
        throw new \Exception('Not implemented.');
    }

    public function getAddressUnspentOutputs($address)
    {
        throw new \Exception('Not implemented.');
    }

    public function getAddressesUnspentOutputs(array $address)
    {
        throw new \Exception('Not implemented.');
    }

    public function getTransactionsByBlock($address)
    {
        throw new \Exception('Not implemented.');
    }

    public function getTransactionsByAddress($address)
    {
        throw new \Exception('Not implemented.');
    }

}