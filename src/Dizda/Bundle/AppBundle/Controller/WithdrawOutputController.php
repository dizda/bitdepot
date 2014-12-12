<?php

namespace Dizda\Bundle\AppBundle\Controller;

use Dizda\Bundle\AppBundle\Entity\Application;
use Dizda\Bundle\AppBundle\Entity\Withdraw;
use Dizda\Bundle\AppBundle\Entity\WithdrawOutput;
use Dizda\Bundle\AppBundle\Request\PostWithdrawOutputRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as REST;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class WithdrawOutputController
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class WithdrawOutputController extends Controller
{
    /**
     * Get list of withdraws
     *
     * @REST\View(serializerGroups={"WithdrawOutputs"})
     *
     * @param Application $application
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getWithdrawOutputsAction(Application $application)
    {
        $withdrawOutputs = $this->get('doctrine.orm.default_entity_manager')
            ->getRepository('DizdaAppBundle:WithdrawOutput')
            ->getWithdrawOutputs($application)
        ;

        return $withdrawOutputs;
    }

    /**
     * @REST\View(serializerGroups={"WithdrawOutputs"})
     *
     * @param Application $application
     * @param Withdraw    $withdrawOutput
     *
     * @return Withdraw
     */
    public function getWithdrawOutputAction(Application $application, Withdraw $withdrawOutput)
    {

        return $withdrawOutput;
    }

    /**
     * @REST\View(serializerGroups={"WithdrawOutputs"})
     *
     * @param Application $application
     * @param Request     $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return WithdrawOutput
     */
    public function postWithdrawOutputsAction(Application $application, Request $request)
    {
        $withdrawOutputSubmitted = (new PostWithdrawOutputRequest($request->request->all()))->options;

        $withdrawOutput = $this->get('dizda_app.manager.withdraw_output')->create($withdrawOutputSubmitted);

        $this->get('doctrine.orm.default_entity_manager')->flush();

        return $withdrawOutput;
    }
}
