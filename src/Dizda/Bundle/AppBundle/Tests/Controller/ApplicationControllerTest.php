<?php

namespace Dizda\Bundle\AppBundle\Tests\Controller;

use Dizda\Bundle\AppBundle\Tests\BaseFunctionalTestController;

/**
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class ApplicationControllerTest extends BaseFunctionalTestController
{
    /**
     * @group functional
     */
    public function testGetApplicationsAction()
    {
        $this->client->request('GET', '/applications.json');

        $json = json_decode($this->client->getResponse()->getContent());

        $this->assertCount(1, $json);
    }

    /**
     * @group functional
     */
    public function testGetApplicationsWithNonAllowedUserAction()
    {
        $em = $this->client->getContainer()->get('doctrine.orm.default_entity_manager');
        $user = $em->getRepository('DizdaUserBundle:User')->findOneByUsername('dizda');
        $user->removeRole('APP_ACCESS_1'); // Remove access to the current app
        $user->addRole('APP_ACCESS_2'); // Add just a role for another app, it should do nothing
        $em->flush();

        $this->client->request('GET', '/applications.json');

        $json = json_decode($this->client->getResponse()->getContent());

        $this->assertCount(0, $json);
    }
}
