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

        $content = $this->client->getResponse()->getContent();

        $this->assertEquals(
            '[{"id":1,"value":"3M2C54k8xit7oLAgSat5PbmAtbhCyp5EqU","is_external":true,"balance":"0.00020000","deposit":{},"transactions":[]},{"id":2,"value":"3QYr3UHFsTbEKVheCRx5CMJSiEECS4ZWX4","is_external":true,"balance":"0.00000000","transactions":[]},{"id":3,"value":"3MxR1yHVpfB7cXULzpetoyNVvUeqhoaJhE","is_external":true,"balance":"0.00000000","transactions":[]},{"id":4,"value":"3L2ryDvAAS4db6GxdMhyTNWhqE9KznxpyC","is_external":false,"balance":"0.00030000","transactions":[{}]},{"id":5,"value":"373sZt2kkNZgaVamtRMmevkRk3NUX98kqV","is_external":false,"balance":"0.00010000","withdraw_change_address":{},"transactions":[{}]}]',
            $content
        );
    }
}
