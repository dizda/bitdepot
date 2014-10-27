<?php

namespace Dizda\Bundle\BlockchainBundle\Client;

use GuzzleHttp\Client;

class HttpClient
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->client = new Client($config);
    }

    public function getClient()
    {
        return $this->client;
    }

}