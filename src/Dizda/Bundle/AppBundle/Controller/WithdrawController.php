<?php

namespace Dizda\Bundle\AppBundle\Controller;

use Dizda\Bundle\AppBundle\Entity\Withdraw;
use Dizda\Bundle\AppBundle\Request\PostWithdrawRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as REST;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WithdrawController extends Controller
{
    /**
     * Get list of withdraws
     *
     * REST\View(serializerGroups={"Withdraws"})
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getWithdrawsAction()
    {
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
    public function getWithdrawAction(Withdraw $withdraw)
    {

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

        if ($withdraw->getId() !== $withdrawSubmitted['id']) {
            throw new NotFoundHttpException();
        }

        $this->get('dizda_app.manager.withdraw')->save($withdraw, $withdrawSubmitted);

        $this->get('doctrine.orm.default_entity_manager')->flush();

        return $withdraw;
    }

    /**
     * @param Withdraw $withdraw
     *
     * @return \StdClass
     */
    public function deleteWithdrawAction(Withdraw $withdraw)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
        $em->remove($withdraw);
        $em->flush();

        return new \StdClass(); // return an empty object {}
    }
}
