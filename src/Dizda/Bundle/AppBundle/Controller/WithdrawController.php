<?php

namespace Dizda\Bundle\AppBundle\Controller;

use Dizda\Bundle\AppBundle\Entity\Withdraw;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as REST;

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
            ->getUnsignedWithdraw()
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
}
