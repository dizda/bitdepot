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
     * @param array $endpoints
     */
    public function __construct(array $endpoints)
    {
        $config = [
            'connect_timeout' => 5,
            'timeout'         => 5
        ];

        // make endpoint's rotation
        $config['base_url'] = $endpoints[rand(0, count($endpoints) - 1)];

        $this->client = new Client($config);
    }

    public function getClient()
    {
        return $this->client;
    }

}