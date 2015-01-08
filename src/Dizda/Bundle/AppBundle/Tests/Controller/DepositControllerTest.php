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

        $content = $this->client->getResponse()->getContent();

        $this->assertEquals(
            '[{"id":1,"type":1,"amount_expected":"0.00020000","amount_filled":"0.00000000","is_fulfilled":false,"is_overfilled":false,"address_external":{"value":"3M2C54k8xit7oLAgSat5PbmAtbhCyp5EqU","is_external":true,"balance":"0.00020000"}}]',
            $content
        );
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
            'amount_expected' => '0.00040000'
        ]);

        $content = $this->client->getResponse()->getContent();

        $this->assertEquals(
            '{"id":2,"type":1,"amount_expected":"0.00040000","amount_filled":"0.00000000","is_fulfilled":false,"is_overfilled":false,"address_external":{"value":"3CzQosnjC24GBezPyP2v1fGBN1DM39LmBT","is_external":true,"balance":"0.00000000"}}',
            $content
        );

        // Get a deposit as a topup address
        $this->client->request('POST', '/deposits.json', [
            'application_id'  => 1,
            'type'            => 2, // Topup
        ]);

        $content = $this->client->getResponse()->getContent();

        $this->assertEquals(
            '{"id":3,"type":2,"amount_filled":"0.00000000","is_fulfilled":false,"is_overfilled":false,"address_external":{"value":"3HqJkrZymZFMJk8ToUL7RnrHutdAtsuFgW","is_external":true,"balance":"0.00000000"}}',
            $content
        );

        // Get number of deposits, to see if they was incremented
        $this->client->request('GET', '/deposits.json');

        $content = json_decode($this->client->getResponse()->getContent());

        $this->assertCount(
            3,
            $content
        );
    }

}
