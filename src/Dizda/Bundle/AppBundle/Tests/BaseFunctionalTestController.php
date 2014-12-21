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
//        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXUyJ9.eyJleHAiOjE0MTkyNjE3MzQsInVzZXJuYW1lIjoiZGl6ZGEiLCJpYXQiOiIxNDE5MTc1MzM0In0.WCnsth8PnY65qJNEyG8Bvgp7uwq0tTrLNeQp-W6u6MOGzt5rWSl8OIp7YBFuiNA9vJxTyHIUiVDnfb5wpX31SVtmFniXaXpo1q743hj6JhPaw0RfVDvhgWOFr3Cx8XHMvc4qakkWtbjgzYAYK8QIPM5XqAcaoKvGcPgXBoyN0R7KzsN3WiynjgWo3q4z3YpQfWIMN6JGBQxI0oMB-qrLmXQcaXZnUZ1iZGylp08IzBXN5ku26xzbrjSm33DDk3EldEbV9r6ITBlO6Lor-VHoDilxby_H2hahDLFswcstzK5dP4YMRDO-1K_aI16wy66AW-fmq4kSK_kG-w3cv4mXws9SuVNxtVor9jUSfV8lvp3Nn9_nAeTA-2EuBjiHZ5QEZfFOYSEe2gmA-sb'));

        $this->client->request(
            'POST',
            '/login_check',
            array(
                '_username' => 'dizda',
                '_password' => 'bambou',
            )
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));
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
