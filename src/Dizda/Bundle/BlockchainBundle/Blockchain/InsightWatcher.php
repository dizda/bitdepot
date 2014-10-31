<?php

namespace Dizda\Bundle\BlockchainBundle\Blockchain;

use Dizda\Bundle\BlockchainBundle\Client\HttpClient;
use Dizda\Bundle\BlockchainBundle\Model\Insight\TransactionList;
use JMS\Serializer\SerializerInterface;

/**
 * Class InsightWatcher
 */
class InsightWatcher extends BlockchainBase implements BlockchainWatcherInterface
{
    /**
     * @var \Dizda\Bundle\BlockchainBundle\Client\HttpClient
     */
    private $client;

    /**
     * @param HttpClient          $http
     * @param SerializerInterface $serializer
     */
    public function __construct(HttpClient $http, SerializerInterface $serializer)
    {
        $this->client = $http->getClient();
        $this->serializer = $serializer;
    }

    public function getAddress($address, $withTransactions = false)
    {
        $response = $this->client->get(sprintf('addr/%s?noTxList=1', $address));

        return $this->serializer->deserialize(
            $response->getBody(),
            'Dizda\Bundle\BlockchainBundle\Model\Insight\Address',
            'json'
        );
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

    /**
     * @param string $address
     *
     * @return array[]
     */
    public function getTransactionsByAddress($address)
    {
        $response = $this->client->get(sprintf('txs/?address=%s', $address));

        /**
         * @var TransactionList
         */
        $transactions = $this->serializer->deserialize(
            $response->getBody(),
            'Dizda\Bundle\BlockchainBundle\Model\Insight\TransactionList',
            'json'
        );

        return $transactions->getTxs();
    }
}
