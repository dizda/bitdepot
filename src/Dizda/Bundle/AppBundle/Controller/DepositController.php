<?php

namespace Dizda\Bundle\AppBundle\Controller;

use Dizda\Bundle\AppBundle\Entity\Withdraw;
use Dizda\Bundle\AppBundle\Request\PostWithdrawRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as REST;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DepositController extends Controller
{
    /**
     * Get list of deposits
     *
     * REST\View(serializerGroups={"Withdraws"})
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getDepositsAction()
    {
        $deposits = $this->get('doctrine.orm.default_entity_manager')
            ->getRepository('DizdaAppBundle:Deposit')
            ->getDeposits()
        ;

        return $deposits;
    }
}
