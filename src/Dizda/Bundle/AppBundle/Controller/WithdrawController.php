<?php

namespace Dizda\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WithdrawController extends Controller
{
    public function getWithdrawsAction()
    {
//        $this->
        $withdraws = $this->get('doctrine.orm.default_entity_manager')
            ->getRepository('DizdaAppBundle:Withdraw')
            ->getUnsignedWithdraw()
        ;

        return $withdraws;
    }
}
