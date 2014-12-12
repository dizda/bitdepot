<?php

namespace Dizda\Bundle\AppBundle\Tests\Controller;

/**
 * Class AddressControllerTest
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class AddressControllerTest extends BaseFunctionalTestController
{

    public function testGetAddressesAction()
    {
        $this->client->request('GET', '/addresses.json');

        $content = $this->client->getResponse()->getContent();

        $this->assertEquals(
            '[{"id":1,"value":"3M2C54k8xit7oLAgSat5PbmAtbhCyp5EqU","is_external":true,"balance":"0.0002","transactions":[]}]',
            $content
        );
    }
}
