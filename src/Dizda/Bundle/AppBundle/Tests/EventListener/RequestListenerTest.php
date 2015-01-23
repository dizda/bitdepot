<?php

namespace Dizda\Bundle\AppBundle\Tests\EventListener;

use Dizda\Bundle\AppBundle\Entity\Application;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTestCase;
use Dizda\Bundle\AppBundle\EventListener\RequestListener;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestListenerTest
 */
class RequestListenerTest extends ProphecyTestCase
{
    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage
     */
    private $tokenStorage;

    /**
     * @var \Dizda\Bundle\AppBundle\EventListener\RequestListener
     */
    private $listener;

    /**
     * RequestListener::onKernelRequest()
     */
    public function testOnKernelRequest()
    {
        $event = $this->prophesize('Symfony\Component\HttpKernel\Event\GetResponseEvent');
        $request = new Request();
        $request->setMethod('POST');
        $request->setRequestFormat('json');

        $tokenInterface = $this->prophesize('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');

        $event->isMasterRequest()->shouldBeCalled()->willReturn(true);
        $event->getRequest()->shouldBeCalledTimes(3)->willReturn($request);
        $this->tokenStorage->getToken()->shouldBeCalled()->willReturn($tokenInterface->reveal());
        $tokenInterface->isAuthenticated()->shouldBeCalled()->willReturn(true);

        $tokenInterface->getUser()->shouldBeCalled()->willReturn((new Application())->setId(333));

        $this->listener->onKernelRequest($event->reveal());
        $this->assertEquals($request->request->all(), ['application_id' => 333]);
    }

    /**
     * RequestListener::onKernelRequest()
     */
    public function testOnKernelRequestIfRequestFormatIsHtml()
    {
        $event = $this->prophesize('Symfony\Component\HttpKernel\Event\GetResponseEvent');
        $request = new Request();
        $request->setMethod('POST');
        $request->setRequestFormat('html');

        $event->isMasterRequest()->shouldBeCalled()->willReturn(true);
        $event->getRequest()->shouldBeCalledTimes(1)->willReturn($request);
        $this->tokenStorage->getToken()->shouldNotBeCalled();

        $this->listener->onKernelRequest($event->reveal());
        $this->assertEquals($request->request->all(), []);
    }

    /**
     * Instantiate
     *
     * @before
     */
    public function setUpObjects()
    {
        $this->tokenStorage = $this->prophesize('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage');
        $this->listener     = new RequestListener(
            $this->tokenStorage->reveal()
        );
    }
}
