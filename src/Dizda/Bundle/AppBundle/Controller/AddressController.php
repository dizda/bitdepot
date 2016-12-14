<?php

namespace Dizda\Bundle\AppBundle\Controller;

use Dizda\Bundle\AppBundle\Request\GetAddressesRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as REST;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AddressController
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class AddressController extends Controller
{
    /**
     * Get list of withdraws
     *
     * @REST\View(serializerGroups={"Addresses"})
     *
     * @param Request $request
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getAddressesAction(Request $request)
    {
        $filters = (new GetAddressesRequest($request->query->all()))->options;

        $this->denyAccessUnlessGranted('access', $filters['application_id']);

        $addresses = $this->get('doctrine.orm.default_entity_manager')
            ->getRepository('DizdaAppBundle:Address')
            ->getAddresses($filters)
        ;

        return $addresses;
    }
}
