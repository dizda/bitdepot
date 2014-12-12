<?php

namespace Dizda\Bundle\AppBundle\Tests\Controller;

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

    public function setUp()
    {
        $this->client = static::createClient();
        $this->em     = $this->client->getContainer()->get('doctrine')->getManager();

        $this->loadFixtures([
            'Dizda\Bundle\AppBundle\DataFixtures\ORM\LoadKeychainData',
            'Dizda\Bundle\AppBundle\DataFixtures\ORM\LoadPubkeyData',
            'Dizda\Bundle\AppBundle\DataFixtures\ORM\LoadAddressData'
        ]);
    }
}
