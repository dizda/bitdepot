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

    protected static $token = null;

    /**
     * Set up
     */
    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->em     = $this->client->getContainer()->get('doctrine')->getManager();

        $this->client->startIsolation();

        $this->login();
    }

    /**
     * Login just once, to speed tests
     */
    private function login()
    {
        if (null === static::$token) {
            // Generate token
            $this->client->request(
                'POST',
                '/login_check',
                array(
                    '_username' => 'dizda',
                    '_password' => 'bambou',
                )
            );

            $data = json_decode($this->client->getResponse()->getContent(), true);
            $this->assertNotNull($data['token']);

            static::$token = $data['token'];
        }

        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', static::$token));
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
