<?php

namespace Dizda\Bundle\AppBundle\Tests\Controller;

use Dizda\Bundle\AppBundle\Tests\BaseFunctionalTestController;

/**
 * Class AddressControllerTest
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class AddressControllerTest extends BaseFunctionalTestController
{
    /**
     * @group functional
     */
    public function testGetAddressesAction()
    {
        $this->client->request('GET', '/addresses.json');

        $json = json_decode($this->client->getResponse()->getContent());

//        $this->assertEquals(
//            '[{"id":1,"value":"3M2C54k8xit7oLAgSat5PbmAtbhCyp5EqU","is_external":true,"balance":"0.00020000","deposit":{},"transactions":[]},{"id":2,"value":"3QYr3UHFsTbEKVheCRx5CMJSiEECS4ZWX4","is_external":true,"balance":"0.00000000","transactions":[]},{"id":3,"value":"3MxR1yHVpfB7cXULzpetoyNVvUeqhoaJhE","is_external":true,"balance":"0.00000000","transactions":[]},{"id":4,"value":"3L2ryDvAAS4db6GxdMhyTNWhqE9KznxpyC","is_external":false,"balance":"0.00030000","transactions":[{}]},{"id":5,"value":"373sZt2kkNZgaVamtRMmevkRk3NUX98kqV","is_external":false,"balance":"0.00010000","withdraw_change_address":{},"transactions":[{}]}]',
//            $content
//        );

        $this->assertEquals('3M2C54k8xit7oLAgSat5PbmAtbhCyp5EqU', $json[0]->value);
        $this->assertTrue($json[0]->is_external);
        $this->assertEquals('0.00020000', $json[0]->balance);
        $this->assertCount(0, $json[0]->transactions);
        $this->assertNotNull($json[0]->updated_at);

        $this->assertEquals('3QYr3UHFsTbEKVheCRx5CMJSiEECS4ZWX4', $json[1]->value);
        $this->assertTrue($json[1]->is_external);
        $this->assertEquals('0.00000000', $json[1]->balance);
        $this->assertCount(0, $json[1]->transactions);
        $this->assertNotNull($json[1]->updated_at);

        $this->assertEquals('3MxR1yHVpfB7cXULzpetoyNVvUeqhoaJhE', $json[2]->value);
        $this->assertTrue($json[2]->is_external);
        $this->assertEquals('0.00000000', $json[2]->balance);
        $this->assertCount(0, $json[2]->transactions);
        $this->assertNotNull($json[2]->updated_at);

        $this->assertEquals('3L2ryDvAAS4db6GxdMhyTNWhqE9KznxpyC', $json[3]->value);
        $this->assertFalse($json[3]->is_external);
        $this->assertEquals('0.00030000', $json[3]->balance);
        $this->assertCount(1, $json[3]->transactions);
        $this->assertNotNull($json[3]->updated_at);

        $this->assertEquals('373sZt2kkNZgaVamtRMmevkRk3NUX98kqV', $json[4]->value);
        $this->assertFalse($json[4]->is_external);
        $this->assertEquals('0.00010000', $json[4]->balance);
        $this->assertCount(1, $json[4]->transactions);
        $this->assertNotNull($json[4]->updated_at);
        $this->assertNotNull($json[4]->withdraw_change_address->updated_at);
    }
}
