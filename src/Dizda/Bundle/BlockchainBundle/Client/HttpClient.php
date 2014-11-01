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
        $config = array_merge($config, [
            'connect_timeout' => 5,
            'timeout'         => 5
        ]);

        $this->client = new Client($config);
    }

    public function getClient()
    {
        return $this->client;
    }

}