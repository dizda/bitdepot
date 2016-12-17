<?php

namespace Dizda\Bundle\AppBundle\Controller;

use Dizda\Bundle\AppBundle\Request\GetAddressesRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as REST;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApplicationController
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class ApplicationController extends Controller
{
    /**
     * Get list of applications
     *
     * @REST\View(serializerGroups={"Applications"})
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getApplicationsAction()
    {
        $applications = $this->get('doctrine.orm.default_entity_manager')
            ->getRepository('DizdaAppBundle:Application')
            ->getApplicationsDashboard()
        ;

        // Only show apps that the user has access to
        foreach ($applications as $key => $app) {
            if (!$this->getUser()->hasRole('APP_ACCESS_'.$app['application']->getId())) {
                unset($applications[$key]);
            }
        }

        return array_values($applications); // reorder array to avoid "{"1":{"application":{"id":2 [...]"
    }
}
