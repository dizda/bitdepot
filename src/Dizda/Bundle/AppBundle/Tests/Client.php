<?php

namespace Dizda\Bundle\AppBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Client as BaseClient;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Client
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 * @see    http://alexandre-salome.fr/blog/Symfony2-Isolation-Of-Tests
 */
class Client extends BaseClient
{
    /**
     * The current DBAL connection.
     *
     * @var \Doctrine\DBAL\Connection
     */
    static protected $connection;

    /**
     * Was this client already requested?
     *
     * @var bool
     */
    protected $requested = false;

    /**
     * @param Request $request
     *
     * @return Request
     */
    protected function doRequest($request)
    {
        if (true === $this->requested) {
            $this->kernel->shutdown();
            $this->kernel->boot();
        }

        $this->startIsolation();
        $this->requested = true;

        return $this->kernel->handle($request);
    }

    /**
     * Starts the isolation process of the client.
     */
    public function startIsolation()
    {
        if (null === self::$connection) {
            self::$connection = $this->getContainer()->get('doctrine.dbal.default_connection');
        } else {
            $this->getContainer()->set('doctrine.dbal.default_connection', static::$connection);
        }

        if (false === $this->requested) {
            self::$connection->beginTransaction();
        }
    }

    /**
     * Stops the isolation process of the client.
     */
    public function stopIsolation()
    {
        if (null !== self::$connection) {
            if (self::$connection->isTransactionActive()) {
                self::$connection->rollback();
            }

            self::$connection->close();
        }

        self::$connection = null;
    }
}
