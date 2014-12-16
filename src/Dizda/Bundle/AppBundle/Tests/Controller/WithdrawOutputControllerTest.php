<?php

namespace Dizda\Bundle\AppBundle\Tests\Controller;

use Dizda\Bundle\AppBundle\Tests\BaseFunctionalTestController;

/**
 * Class WithdrawOutputControllerTest
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class WithdrawOutputControllerTest extends BaseFunctionalTestController
{

    /**
     * @group functional
     */
    public function testGetWithdrawOutputsAction()
    {
        $app = $this->em->getRepository('DizdaAppBundle:Application')->findOneByName('Application-Fixture');

        $this->client->request('GET', sprintf('/withdraws/%d/outputs.json', $app->getId()));

        $content = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals('0.0001', $content[0]->amount);
        $this->assertEquals('1LGTbdVSEbD9C37qXcpvVJ1egdBu8jYSeV', $content[0]->to_address);
        $this->assertTrue($content[0]->is_accepted);
        $this->assertEquals(1, $content[0]->withdraw->id);
    }

    /**
     * Not used yet.
     *
     * @group functional
     */
    public function testGetWithdrawOutputAction()
    {
        $app = $this->em->getRepository('DizdaAppBundle:Application')->findOneByName('Application-Fixture');
        $withdrawOutput = $this->em->getRepository('DizdaAppBundle:WithdrawOutput')->findOneByToAddress('1LGTbdVSEbD9C37qXcpvVJ1egdBu8jYSeV');

        $this->client->request('GET', sprintf(
            '/withdraws/%d/outputs/%d.json',
            $app->getId(),
            $withdrawOutput->getId()
        ));

        $content = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(1, $content->id);
    }

    /**
     * @group functional
     */
    public function testPostWithdrawOutputsAction()
    {
        $this->client->request('POST', sprintf('/withdraws/%d/outputs.json', 1), [
            'application_id' => /* application */ 1,
            'to_address'     => '1Cxtev7KLyEen5UxqsBYn6JqcZREm28DXh',
            'amount'         => '0.00111',
            'is_accepted'    => true,
            'reference'      => 'coucou'
        ]);

        $content = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals('0.00111', $content->amount);
        $this->assertEquals('1Cxtev7KLyEen5UxqsBYn6JqcZREm28DXh', $content->to_address);
        $this->assertTrue($content->is_accepted);
        $this->assertEquals('coucou', $content->reference);
    }
}
