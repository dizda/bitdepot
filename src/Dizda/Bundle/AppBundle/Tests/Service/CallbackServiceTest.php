<?php

namespace Dizda\Bundle\AppBundle\Tests\Service;

use Dizda\Bundle\AppBundle\Service\CallbackService;
use Dizda\Bundle\AppBundle\Tests\BaseFunctionalTestController;

/**
 * Class CallbackServiceTest
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class CallbackServiceTest extends BaseFunctionalTestController
{
    private $serializer;
    private $httpMock;
    private $dummyResponse;

    /**
     * @group functional
     */
    public function testDepositExpectedFilling()
    {
        $deposit = $this->em->getRepository('DizdaAppBundle:Deposit')->find(1);

        $body = ['body' => '{"id":1,"amount_expected":"0.00020000","amount_filled":"0.00000000","is_fulfilled":false,"is_overfilled":false,"address_external":{"value":"3M2C54k8xit7oLAgSat5PbmAtbhCyp5EqU","balance":"0.00020000"}}'];

        $this->dummyResponse->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200)
        ;

        $this->httpMock
            ->expects($this->once())
            ->method('post')
            ->with($this->equalTo('http://callback-test.com/callback/deposit/expected.json'), $this->equalTo($body))
            ->will($this->returnValue($this->dummyResponse))
        ;

        $service = new CallbackService($this->serializer, $this->httpMock);
        $return = $service->depositExpectedFilling($deposit);

        $this->assertTrue($return);
    }

    /**
     * @group functional
     */
    public function testWithdrawOutputWithdrawn()
    {
        $withdrawOutput = $this->em->getRepository('DizdaAppBundle:WithdrawOutput')->find(1);

        $body = ['body' => '{"id":1,"amount":"0.00010000","to_address":"1LGTbdVSEbD9C37qXcpvVJ1egdBu8jYSeV","is_accepted":true,"withdraw":{"txid":"431c5231114ce2d00125ea4a88f4e4637b80fef1117a0b20606204e45cc3678f","withdrawed_at":"2015-01-25T11:11:11+0100"}}'];

        $this->dummyResponse->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200)
        ;

        $this->httpMock
            ->expects($this->once())
            ->method('post')
            ->with($this->equalTo('http://callback-test.com/callback/withdraw/output.json'), $this->equalTo($body))
            ->will($this->returnValue($this->dummyResponse))
        ;

        $service = new CallbackService($this->serializer, $this->httpMock);
        $return = $service->withdrawOutputWithdrawn($withdrawOutput);

        $this->assertTrue($return);
    }

    /**
     * Instantiate
     *
     * @before
     */
    public function setUpObjects()
    {
        $this->serializer = $this->getContainer()->get('serializer');
        $this->httpMock   = $this->getMockBuilder('Dizda\Bundle\AppBundle\Service\HttpService')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->dummyResponse = $this->getMockBuilder('GuzzleHttp\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }
}
