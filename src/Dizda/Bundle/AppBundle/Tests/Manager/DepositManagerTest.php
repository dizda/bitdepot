<?php

namespace Dizda\Bundle\AppBundle\Tests\Manager;

use Dizda\Bundle\AppBundle\Entity\Address;
use Dizda\Bundle\AppBundle\Entity\Application;
use Dizda\Bundle\AppBundle\Manager\DepositManager;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTestCase;

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
        $appRepo = $this->prophesize('Doctrine\ORM\EntityRepository');
        $addRepo = $this->prophesize('Dizda\Bundle\AppBundle\Repository\AddressRepository');


        $em->getRepository('DizdaAppBundle:Application')->shouldBeCalled()->willReturn($appRepo->reveal());
        $appRepo->find(Argument::exact(11))->shouldBeCalled()->willReturn(new Application());

        $em->getRepository('DizdaAppBundle:Address')->shouldBeCalled()->willReturn($addRepo->reveal());
        $addRepo->getOneFreeAddress()->shouldBeCalled()->willReturn(new Address());

        $em->persist(Argument::type('Dizda\Bundle\AppBundle\Entity\Deposit'))->shouldBeCalled();

        $manager = new DepositManager($em->reveal(), $logger->reveal(), $dispatcher->reveal());
        $return = $manager->create([
            'application_id' => 11,
            'type'           => 1,
            'amount_expected'=> '77.00000000'
        ]);

        $this->assertEquals(1, $return->getType());
        $this->assertEquals('77.00000000', $return->getAmountExpected());
    }
}
