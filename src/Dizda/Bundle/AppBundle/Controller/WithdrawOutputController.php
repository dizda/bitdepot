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
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getOutputsAction(Request $request)
    {
        $this->denyAccessUnlessGranted('access', $request->get('application_id'));

        $withdrawOutputs = $this->get('doctrine.orm.default_entity_manager')
            ->getRepository('DizdaAppBundle:WithdrawOutput')
//            ->getWithdrawOutputs($application)
            ->getWithdrawOutputs($request->query->all())
        ;

        return $withdrawOutputs;
    }

    /**
     * @REST\View(serializerGroups={"WithdrawOutputs"})
     *
     * @param Withdraw $withdrawOutput
     *
     * @return Withdraw
     */
    public function getOutputAction(Request $request, Withdraw $withdrawOutput)
    {
        $this->denyAccessUnlessGranted('access', $request->get('application_id'));

        return $withdrawOutput;
    }

    /**
     * @REST\View(serializerGroups={"WithdrawOutputs"})
     *
     * @param Request     $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return WithdrawOutput
     */
    public function postOutputsAction(Request $request)
    {
        $request->request->add(['application_id' => $request->get('application_id')]);

        $withdrawOutputSubmitted = (new PostWithdrawOutputRequest($request->request->all()))->options;

        $this->denyAccessUnlessGranted('access', $withdrawOutputSubmitted['application_id']);

        $withdrawOutput = $this->get('dizda_app.manager.withdraw_output')->create($withdrawOutputSubmitted);

        $this->get('doctrine.orm.default_entity_manager')->flush();

        return $withdrawOutput;
    }
}
