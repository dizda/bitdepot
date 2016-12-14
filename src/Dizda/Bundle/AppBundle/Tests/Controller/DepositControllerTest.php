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
        $this->client->request('GET', '/deposits.json?application_id=1');

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
        $this->client->request('GET', '/deposits.json?application_id=1');

        $content = json_decode($this->client->getResponse()->getContent());

        $this->assertCount(
            3,
            $content
        );
    }

    /**
     * @group functional
     */
    public function testPostDepositsFiatAmountAction()
    {
        // Get a deposit with an expected amount
        $this->client->request('POST', '/deposits.json', [
            'application_id'  => 1,
            'type'            => 1, // Expected
            'amount_expected_fiat' => [
                'amount'   => '3999',
                'currency' => 'EUR'
            ],
            'reference'       => 'test_reference'
        ]);

        $content = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(1, $content->type);
        $this->assertTrue(bccomp($content->amount_expected, '0.01', 8) === 1); // Verify that blockchain.info return a result larger than 0.01 btc for 39€
        $this->assertEquals('0.00000000', $content->amount_filled);
        $this->assertFalse($content->is_fulfilled);
        $this->assertFalse($content->is_overfilled);
        $this->assertEquals('3CzQosnjC24GBezPyP2v1fGBN1DM39LmBT', $content->address_external->value);
        $this->assertEquals('0.00000000', $content->address_external->balance);
        $this->assertNotNull($content->address_external->created_at);
        $this->assertNotNull($content->created_at);
    }


    /**
     * @group functional
     */
    public function testPostDepositsActionWithNoAppIdShouldFail()
    {
        // Get a deposit with an expected amount
        $this->client->request('POST', '/deposits.json', [
//            'application_id' => 1,
            'type' => 1, // Expected
            'amount_expected' => '0.00040000',
            'reference' => 'test_reference'
        ]);

        $this->assertFalse($this->client->getResponse()->isSuccessful());
    }

    /**
     * @group functional
     */
    public function testPostDepositsActionWithNoAllowedUserShouldFail()
    {
        $em = $this->client->getContainer()->get('doctrine.orm.default_entity_manager');
        $user = $em->getRepository('DizdaUserBundle:User')->findOneByUsername('dizda');
        $user->removeRole('APP_ACCESS_1'); // Remove access to the current app
        $em->flush();

        // Get a deposit with an expected amount
        $this->client->request('POST', '/deposits.json', [
            'application_id' => 1,
            'type' => 1, // Expected
            'amount_expected' => '0.00040000',
            'reference' => 'test_reference'
        ]);

        $response = $this->client->getResponse();
        $json = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('Forbidden', $json->error->message);
        $this->assertEquals('You do not have the necessary permissions', $json->error->exception[0]->message);
    }
}
