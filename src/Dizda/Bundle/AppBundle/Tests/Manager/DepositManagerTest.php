<?php

namespace Dizda\Bundle\AppBundle\Tests\Manager;

use Dizda\Bundle\AppBundle\Entity\Address;
use Dizda\Bundle\AppBundle\Entity\Application;
use Dizda\Bundle\AppBundle\Manager\DepositManager;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTestCase;
use Dizda\Bundle\AppBundle\Request\PostDepositsRequest;

/**
 * Class DepositManagerTest
 */
class DepositManagerTest extends ProphecyTestCase
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

        $appRepo = $this->prophesize('Doctrine\ORM\EntityRepository');
        $addRepo = $this->prophesize('Dizda\Bundle\AppBundle\Repository\AddressRepository');


        $em->getRepository('DizdaAppBundle:Application')->shouldBeCalled()->willReturn($appRepo->reveal());
        $appRepo->find(Argument::exact(11))->shouldBeCalled()->willReturn(new Application());

        $addressManager->create(Argument::type('Dizda\Bundle\AppBundle\Entity\Application'), Argument::exact(true));

        $em->persist(Argument::type('Dizda\Bundle\AppBundle\Entity\Deposit'))->shouldBeCalled();

        $manager = new DepositManager($em->reveal(), $logger->reveal(), $dispatcher->reveal(), $addressManager->reveal());
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
}
