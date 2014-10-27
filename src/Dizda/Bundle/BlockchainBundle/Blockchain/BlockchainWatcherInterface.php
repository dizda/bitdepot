<?php

namespace Dizda\Bundle\BlockchainBundle\Blockchain;

use Dizda\Bundle\BlockchainBundle\Client\HttpClient;
use JMS\Serializer\SerializerInterface;

interface BlockchainWatcherInterface
{

    public function __construct(HttpClient $client, SerializerInterface $serializer);

    public function getAddress($address, $withTransactions);
    public function getAddresses(array $addresses, $withTransactions);

    public function getTransaction($txid);

    public function getAddressUnspentOutputs($address);
    public function getAddressesUnspentOutputs(array $address);

    public function getTransactionsByBlock($address);
    public function getTransactionsByAddress($address);

}