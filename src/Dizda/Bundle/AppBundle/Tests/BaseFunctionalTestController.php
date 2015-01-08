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
        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXUyJ9.eyJleHAiOjE0MjA3NjA2MDMsInVzZXJuYW1lIjoiZGl6ZGEiLCJpYXQiOiIxNDIwNjc0MjAzIn0.IEEZwV_USIfIoC9o2k4aDrDRmfdX0c2mPJRZpujRFc3gi2n2wVsKhswfYI0GeESfAkCoWr87U3Xwx5AkiQKALXV1nYgZpXAhYkM_hEX1dLMVjzAU81H7W5jN_WUu1W3Iu_hN2thf4i2b4UgOwypqiegCyKV7aNu_3eSToLgadgmIZO5sfRAkoKlL-r6dDrV3583ypiSrhWHA74vOcFK12ZgD5NQPC-OvwrR8REwbAlDDAK5j67WRT6yWlMqUlMs7-5KBqz9tq7UjHpYJf0hKEclJL7asi_QB1eU96IzhYsGInd6ZpCiOlwFnD6LO5aI-Gn7E7jDGP0p29wtr6fYTFILUXzpZwbwo6q6z71bQ8O-3FXvNlg40u6VAzsULksOwHUzl1Q6uiOf9dXC12dVrsnOFXxqvKXZ7HqiKBkwY21RkTkOTMWDt02Hghp6xgjrCH5wuITYYEs977mFu8Aw2tU4GTRkT2ratroDOAZZcv-J-8t6xFQkVm_bnZQuzjkuuxG_oNryJFRu1HPzUHhya8OwnYmC27dneL2YVGHI_nmalpIh6LQbV5eLtCUaqqjHlVTdnOde1DPLc4pBxhv7lLFFm1e3eVAduipF6wA-YzjIcP3raT9hY0lEoLaKUtDdljRxhMaFpLsBNgoVgJm0fgGiqIJkIVrrbXVN3eoD_d2s'));

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
//        echo PHP_EOL . $data['token'] . PHP_EOL; // Token
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
