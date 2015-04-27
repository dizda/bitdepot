<?php

namespace Dizda\Bundle\AppBundle\Tests\Controller;

use Dizda\Bundle\AppBundle\Tests\BaseFunctionalTestController;

/**
 * Class DepositControllerTest
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class DepositControllerTest extends BaseFunctionalTestController
{

    /**
     * @group functional
     */
    public function testGetDepositsAction()
    {
        $this->client->request('GET', '/deposits.json');

        $content = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(1, $content[0]->type);
        $this->assertEquals('0.00020000', $content[0]->amount_expected);
        $this->assertEquals('0.00000000', $content[0]->amount_filled);
        $this->assertFalse($content[0]->is_fulfilled);
        $this->assertFalse($content[0]->is_overfilled);
        $this->assertEquals('3M2C54k8xit7oLAgSat5PbmAtbhCyp5EqU', $content[0]->address_external->value);
        $this->assertEquals('0.00020000', $content[0]->address_external->balance);
        $this->assertNotNull($content[0]->address_external->created_at);
        $this->assertNotNull($content[0]->created_at);
    }

    /**
     * @group functional
     */
    public function testPostDepositsAction()
    {
        // Get a deposit with an expected amount
        $this->client->request('POST', '/deposits.json', [
            'application_id'  => 1,
            'type'            => 1, // Expected
            'amount_expected' => '0.00040000',
            'reference'       => 'test_reference'
        ]);

        $content = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(1, $content->type);
        $this->assertEquals('0.00040000', $content->amount_expected);
        $this->assertEquals('0.00000000', $content->amount_filled);
        $this->assertFalse($content->is_fulfilled);
        $this->assertFalse($content->is_overfilled);
        $this->assertEquals('3CzQosnjC24GBezPyP2v1fGBN1DM39LmBT', $content->address_external->value);
        $this->assertEquals('0.00000000', $content->address_external->balance);
        $this->assertNotNull($content->address_external->created_at);
        $this->assertNotNull($content->created_at);

        // Get a deposit as a topup address
        $this->client->request('POST', '/deposits.json', [
            'application_id'  => 1,
            'type'            => 2, // Topup
        ]);

        $content = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(2, $content->type);
        $this->assertObjectNotHasAttribute('amount_expected', $content);
        $this->assertEquals('0.00000000', $content->amount_filled);
        $this->assertFalse($content->is_fulfilled);
        $this->assertFalse($content->is_overfilled);
        $this->assertEquals('3HqJkrZymZFMJk8ToUL7RnrHutdAtsuFgW', $content->address_external->value);
        $this->assertEquals('0.00000000', $content->address_external->balance);
        $this->assertNotNull($content->address_external->created_at);
        $this->assertNotNull($content->created_at);

        // Get number of deposits, to see if they was incremented
        $this->client->request('GET', '/deposits.json');

        $content = json_decode($this->client->getResponse()->getContent());

        $this->assertCount(
            3,
            $content
        );
    }

}
