<?php

namespace Dizda\Bundle\AppBundle\Tests\Manager;

use Dizda\Bundle\AppBundle\Tests\BasicUnitTest;
use Dizda\Bundle\AppBundle\Entity\Application;
use Dizda\Bundle\AppBundle\Manager\WithdrawOutputManager;
use Dizda\Bundle\AppBundle\Request\PostWithdrawOutputRequest;
use Prophecy\Argument;

/**
 * Class WithdrawOutputManagerTest
 */
class WithdrawOutputManagerTest extends BasicUnitTest
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var \Dizda\Bundle\AppBundle\Manager\WithdrawOutputManager
     */
    private $manager;

    /**
     * WithdrawOutputManager::create()
     */
    public function testCreate()
    {
        $data = [
            'amount'         => '0.00010000',
            'to_address'     => '1LGTbdVSEbD9C37qXcpvVJ1egdBu8jYSeV',
            'reference'      => 'referenceBitch!',
            'application_id' => 10
        ];
        $data = new PostWithdrawOutputRequest($data);

        $appRepo = $this->prophesize('Doctrine\ORM\EntityRepository');

        $this->em->getRepository('DizdaAppBundle:Application')->shouldBeCalled()->willReturn($appRepo->reveal());
        $appRepo->find(Argument::exact(10))->shouldBeCalled()->willReturn(new Application());

        $this->em->persist(Argument::type('Dizda\Bundle\AppBundle\Entity\WithdrawOutput'))->shouldBeCalled();

        $return = $this->manager->create($data->options);

        $this->assertEquals('0.00010000', $return->getAmount());
        $this->assertEquals('1LGTbdVSEbD9C37qXcpvVJ1egdBu8jYSeV', $return->getToAddress());
        $this->assertTrue($return->getIsAccepted());
        $this->assertEquals('referenceBitch!', $return->getReference());
        $this->assertNull($return->getWithdraw());
    }

    /**
     * Instantiate
     *
     * @before
     */
    public function setUpObjects()
    {
        $this->em           = $this->prophesize('Doctrine\ORM\EntityManager');
        $this->logger       = $this->prophesize('Psr\Log\LoggerInterface');
        $this->dispatcher   = $this->prophesize('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->manager      = new WithdrawOutputManager(
            $this->em->reveal(),
            $this->logger->reveal(),
            $this->dispatcher->reveal()
        );
    }
}
