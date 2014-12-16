<?php

namespace Dizda\Bundle\AppBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Client as BaseClient;

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
     */
    static protected $connection;

    /**
     * Was this client already requested?
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

//    protected function injectConnection()
//    {
//        if (null === self::$connection) {
//            self::$connection = $this->getContainer()->get('doctrine.dbal.default_connection');
//        } else {
//            if (! $this->requested) {
//                self::$connection->rollback();
//            }
//            $this->getContainer()->set('doctrine.dbal.default_connection', self::$connection);
//        }
//
//        if (! $this->requested) {
//            self::$connection->beginTransaction();
//        }
//    }

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
