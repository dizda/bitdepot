<?php

namespace Dizda\Bundle\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class ApplicationRepository
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class ApplicationRepository extends EntityRepository
{

    /**
     * @return array
     */
    public function getApplicationsDashboard()
    {
        $qb = $this->createQueryBuilder('app')
            ->select(
                'app as application',
                'SUM(adr.balance) as balanceAvailable',
                'SUM(d.amountFilled) as amountFilled'
            )
            ->join('app.addresses', 'adr')
            ->leftJoin('adr.deposit', 'd')
            ->orderBy('app.id', 'ASC')
        ;

        return $qb->getQuery()->getResult();
    }
}
