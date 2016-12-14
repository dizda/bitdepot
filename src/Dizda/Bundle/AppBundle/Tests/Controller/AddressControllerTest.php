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
        $this->client->request('GET', '/addresses.json?application_id=1');

        $json = json_decode($this->client->getResponse()->getContent());

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

    /**
     * @group functional
     */
    public function testGetAddressesActionWithNonAllowedUser()
    {
        $em = $this->client->getContainer()->get('doctrine.orm.default_entity_manager');
        $user = $em->getRepository('DizdaUserBundle:User')->findOneByUsername('dizda');
        $user->removeRole('APP_ACCESS_1'); // Remove access to the current app
        $user->addRole('APP_ACCESS_2'); // Add just a role for another app, it should do nothing
        $em->flush();

        $this->client->request('GET', '/addresses.json?application_id=1');

        $response = $this->client->getResponse();
        $json = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('Forbidden', $json->error->message);
        $this->assertEquals('You do not have the necessary permissions', $json->error->exception[0]->message);
    }
}
