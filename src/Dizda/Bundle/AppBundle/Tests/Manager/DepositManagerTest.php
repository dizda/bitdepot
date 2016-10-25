<?php

namespace Dizda\Bundle\AppBundle\Tests\Manager;

use AppBundle\Tests\BasicUnitTest;
use Dizda\Bundle\AppBundle\Entity\Address;
use Dizda\Bundle\AppBundle\Entity\Application;
use Dizda\Bundle\AppBundle\Manager\DepositManager;
use Prophecy\Argument;
use Dizda\Bundle\AppBundle\Request\PostDepositsRequest;

/**
 * Class DepositManagerTest
 */
class DepositManagerTest extends BasicUnitTest
{

    /**
     * DepositManager::create()
     */
    public function testCreateTypeOne()
    {
        $em = $this->prophesize('Doctrine\ORM\EntityManager');
        $logger = $this->prophesize('Psr\Log\LoggerInterface');
        $dispatcher = $this->prophesize('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $addressManager = $this->prophesize('Dizda\Bundle\AppBundle\Manager\AddressManager');
        $httpService = $this->prophesize('Dizda\Bundle\AppBundle\Service\HttpService');

        $appRepo = $this->prophesize('Doctrine\ORM\EntityRepository');
        $addRepo = $this->prophesize('Dizda\Bundle\AppBundle\Repository\AddressRepository');


        $em->getRepository('DizdaAppBundle:Application')->shouldBeCalled()->willReturn($appRepo->reveal());
        $appRepo->find(Argument::exact(11))->shouldBeCalled()->willReturn(new Application());

        $addressManager->create(Argument::type('Dizda\Bundle\AppBundle\Entity\Application'), Argument::exact(true));

        $em->persist(Argument::type('Dizda\Bundle\AppBundle\Entity\Deposit'))->shouldBeCalled();

        $manager = new DepositManager($em->reveal(), $logger->reveal(), $dispatcher->reveal(), $addressManager->reveal(), $httpService->reveal());
        $data    = [
            'application_id' => 11,
            'type'           => 1,
            'amount_expected'=> '77.00000000'
        ];
        $data = new PostDepositsRequest($data);

        $return = $manager->create($data->options);

        $this->assertEquals(1, $return->getType());
        $this->assertEquals('77.00000000', $return->getAmountExpected());
    }

    public function testCalculateAmountIfFiatPriceSuccess()
    {
        $em = $this->prophesize('Doctrine\ORM\EntityManager');
        $logger = $this->prophesize('Psr\Log\LoggerInterface');
        $dispatcher = $this->prophesize('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $addressManager = $this->prophesize('Dizda\Bundle\AppBundle\Manager\AddressManager');
        $httpService = $this->prophesize('Dizda\Bundle\AppBundle\Service\HttpService');
        $response = $this->prophesize('GuzzleHttp\Message\ResponseInterface');

        $manager = new DepositManager($em->reveal(), $logger->reveal(), $dispatcher->reveal(), $addressManager->reveal(), $httpService->reveal());

        $httpService->get('https://blockchain.info/ticker')->shouldBeCalled()->willReturn($response->reveal());

        $response->getStatusCode()->shouldBeCalled()->willReturn(200);
        $response->json()->shouldBeCalled()->willReturn(['EUR' => [
            '15m' => 602.22
        ]]);

        $return = $manager->calculateAmountIfFiatPrice([
            'amount_expected_fiat' => [
                'amount'   => '3999',
                'currency' => 'EUR'
            ]
        ]);

        $this->assertEquals('0.06640430', $return['amount_expected']);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Blockchain.info is not reachable
     */
    public function testCalculateAmountIfFiatPriceBlockchainUnreachable()
    {
        $em = $this->prophesize('Doctrine\ORM\EntityManager');
        $logger = $this->prophesize('Psr\Log\LoggerInterface');
        $dispatcher = $this->prophesize('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $addressManager = $this->prophesize('Dizda\Bundle\AppBundle\Manager\AddressManager');
        $httpService = $this->prophesize('Dizda\Bundle\AppBundle\Service\HttpService');
        $response = $this->prophesize('GuzzleHttp\Message\ResponseInterface');

        $manager = new DepositManager($em->reveal(), $logger->reveal(), $dispatcher->reveal(), $addressManager->reveal(), $httpService->reveal());

        $httpService->get('https://blockchain.info/ticker')->shouldBeCalled()->willReturn($response->reveal());

        $response->getStatusCode()->shouldBeCalled()->willReturn(500);

        $manager->calculateAmountIfFiatPrice([
            'amount_expected_fiat' => [
                'amount'   => '3999',
                'currency' => 'EUR'
            ]
        ]);
    }
}
