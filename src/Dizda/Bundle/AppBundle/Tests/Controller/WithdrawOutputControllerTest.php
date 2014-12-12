<?php

namespace Dizda\Bundle\AppBundle\Tests\Controller;

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
        $this->client->request('GET', sprintf('/withdraws/%d/outputs.json', 1));

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
        $this->client->request('GET', sprintf(
            '/withdraws/%d/outputs/%d.json',
            /* application_id */ 1,
            /* withdrawOutput */ 1
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
            'application_id' => 1,
            'to_address'     => '1Cxtev7KLyEen5UxqsBYn6JqcZREm28DXh',
            'amount'         => '0.00111',
            'is_accepted'    => true,
            'reference'      => 'coucou'
        ]);

        $content = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(3, $content->id);
        $this->assertEquals('0.00111', $content->amount);
        $this->assertEquals('1Cxtev7KLyEen5UxqsBYn6JqcZREm28DXh', $content->to_address);
        $this->assertTrue($content->is_accepted);
        $this->assertEquals('coucou', $content->reference);
    }
}
