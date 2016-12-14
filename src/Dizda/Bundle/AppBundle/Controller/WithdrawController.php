<?php

namespace Dizda\Bundle\AppBundle\Controller;

use Dizda\Bundle\AppBundle\Entity\Withdraw;
use Dizda\Bundle\AppBundle\Request\PostWithdrawRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as REST;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class WithdrawController
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class WithdrawController extends Controller
{
    /**
     * Get list of withdraws
     *
     * @REST\View(serializerGroups={"Withdraws"})
     * @Security("has_role('WITHDRAW_LIST')")
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getWithdrawsAction(Request $request)
    {
        $this->denyAccessUnlessGranted('access', $request->get('application_id'));

        $withdraws = $this->get('doctrine.orm.default_entity_manager')
            ->getRepository('DizdaAppBundle:Withdraw')
            ->getWithdraws()
        ;

        return $withdraws;
    }

    /**
     * @REST\View(serializerGroups={"WithdrawDetail"})
     *
     * @param Withdraw $withdraw
     *
     * @return Withdraw
     */
    public function getWithdrawAction(Request $request, Withdraw $withdraw)
    {
        $this->denyAccessUnlessGranted('access', $request->get('application_id'));

        return $withdraw;
    }

    /**
     * @REST\View(serializerGroups={"WithdrawDetail"})
     *
     * @param Request  $request
     * @param Withdraw $withdraw
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return Withdraw
     */
    public function postWithdrawAction(Request $request, Withdraw $withdraw)
    {
        $withdrawSubmitted = (new PostWithdrawRequest($request->request->all()))->options;

        $this->denyAccessUnlessGranted('access', $withdrawSubmitted['application_id']);

        if ($withdraw->getId() !== $withdrawSubmitted['id']) {
            throw new NotFoundHttpException();
        }

        $this->get('dizda_app.manager.withdraw')->save($withdraw, $withdrawSubmitted);

        $this->get('doctrine.orm.default_entity_manager')->flush();

        return $withdraw;
    }

    /**
     * @param Request  $request
     * @param Withdraw $withdraw
     *
     * @return \StdClass
     */
    public function deleteWithdrawAction(Request $request, Withdraw $withdraw)
    {
        $this->denyAccessUnlessGranted('access', $request->get('application_id'));

        $em = $this->get('doctrine.orm.default_entity_manager');
        $em->remove($withdraw);
        $em->flush();

        return new \StdClass(); // return an empty object {}
    }
}
