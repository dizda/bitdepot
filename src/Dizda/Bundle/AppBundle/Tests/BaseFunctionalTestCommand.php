<?php

namespace Dizda\Bundle\AppBundle\Tests;

use Dizda\Bundle\AppBundle\Tests\Fixtures;
use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class BaseFunctionalTestCommand
 */
class BaseFunctionalTestCommand extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em = null;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $this->getContainer()->get('doctrine.dbal.default_connection')->beginTransaction();
    }

    /**
     * Tear down
     */
    public function tearDown()
    {
        $this->getContainer()->get('doctrine.dbal.default_connection')->rollBack();
    }
}
