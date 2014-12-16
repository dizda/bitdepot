<?php

namespace Dizda\Bundle\AppBundle\Tests;

use Dizda\Bundle\AppBundle\Tests\Fixtures;
use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class BaseFunctionalTestController
 */
class BaseFunctionalTestController extends WebTestCase
{

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client = null;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em = null;

    /**
     * Set up
     */
    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->em     = $this->client->getContainer()->get('doctrine')->getManager();

        $this->client->startIsolation();
    }

    /**
     * Tear down
     */
    public function tearDown()
    {
        if (null !== $this->client) {
            $this->client->stopIsolation();
        }
        parent::tearDown();
    }
}
