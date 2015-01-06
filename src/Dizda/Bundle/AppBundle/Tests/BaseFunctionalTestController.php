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
        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXUyJ9.eyJleHAiOjE0MjA2NTQ1MTIsInVzZXJuYW1lIjoiZGl6ZGEiLCJpYXQiOiIxNDIwNTY4MTEyIn0.nAyAA0w2NfLbYxl5LQfzchA8zHSuuZph2kpb87EKahh_MUKX73wdab2PkH4PIJZwSE7QeDTRxxEddTC8OyuQIpWYF5btlgZ8qyUcyeTCcuLhk3KmmBH8XTBceLRjgzc55aicc0pgw4gnLdUfeYLuak-WauA2QkC3VAmVgNi0oa1lfJyMv_EaPPSQZjWIC5cAUJwAFdGff_FLGcwl8zk5cwL9jaOIOAcPKYa_E0PtZNnrgBwV7y8wjYeTBLWwsghJzlBNjGgTBSB-cchSVQ7_bYIHtUoAmISIvRZbRiJI6T-9zHwgSf_VVH0bR4ePUKeaumBy_hgikItcoX9vQVVOsOCYTbhRV-B4rAjGzXYmUMvN66tKD8UnvfjoY67VGT4Jx_9gs2YIl_nPfGDrFG5Nea3krqDvHpiJeTLdAmmiXqop282Ew2X7GpPO4RBQ1gBITPdI6mTKN-8mC0HoDFs9zkP94YxkVBI2EKEofYwl-W0IeVD521uNOOFVmPBjukfCtGPxneSUiFti4-lrfQ4-G4NjssbvBIOaZ7CvsxOKuZQzLhI-y-Z_oxjvqiFsenIbIrWfeyySqbw-nCQcdpzxQcV287nottz3JB94Se2_xJgwC4qQNq2TOVqk_LXjfj7OsCK1egbhgM1rmtyk2rh1J1ypnFApAwPHAhF4vjVWyWs'));

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
