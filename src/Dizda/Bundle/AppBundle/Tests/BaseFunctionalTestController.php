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

        // Login as dizda via JWT Token
        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXUyJ9.eyJleHAiOjE0MTkzNDYwNTcsInVzZXJuYW1lIjoiZGl6ZGEiLCJpYXQiOiIxNDE5MjU5NjU3In0.v446yY7SvuFRGtO6qPJreVVWXyBwThPf6O5FjMrbITq5P9WbBSg7IugIIZXZr0iobb59fXoJl9KYM42SHRQxkuWkEj3Fpifz99K_CTwKU9Asb02TTqcwu_k5L7GhixW106A-SvXBNc2l6a54xLyB2YrEPdk9PaszIHtr3i2vwxM1PgFWoMJNOkE6mCHvlPWXlEHDqEVg9JW1Lb9o5OzwCMNy4L5OIA_rGOfvwlk7mHKbohXrj6Da5QMFAdhNRqpL4XXNCUsa_fPzQAem0WiEl5wr8kTkJwmgJSqZVFqGAYUb7TWq8LbCGJCWpsEcSfKEl57nUwNFdgi9Me-nMIUXnCKDP5g2PKDx7JSraKix9l7W50hFNP20-iVZAkqND5ZQdTNH34P5W2bKl6OZwpx3OKsgs4YJ672H5ZHEaKWuqQhk1GrwHPMGPNleG43SE_6ur_SjcjAwj3jmDhT-wYO-WHWAiDkgH2q8AviInplJ6i0xOd18vSiGwMYQXSm3rHCb_eGOskzxa7TquR1-4I56JHe03IfNMPkOIz-5ouvXUi2IORCda8scmiQrpq5_bt-KO0IKRpDQDgPdDbemOf7cWTNxMpqC7sVwWI2oQB5pRHxCsbxFqyUFcrJor71gBDYDS5cESLuysOEXxfIJ4Ci-M7QmIk_yHpz4Adomu5s3XlQ'));

//        // Regenerate token
//        $this->client->request(
//            'POST',
//            '/login_check',
//            array(
//                '_username' => 'dizda',
//                '_password' => 'bambou',
//            )
//        );
//
//        $data = json_decode($this->client->getResponse()->getContent(), true);
////        echo $data['token']; // Token
//        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));
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
