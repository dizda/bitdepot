<?php

namespace Dizda\Bundle\AppBundle\Repository;

use Dizda\Bundle\AppBundle\Entity\AddressTransaction;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

/**
 * AddressRepository
 */
class AddressRepository extends EntityRepository
{

    public function getOneFreeAddress($isExternal = true)
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.deposit', 'd')
            ->andWhere('d.id is NULL')
            ->andWhere('a.isExternal = :external')
            // TODO: where application id || keychain
            ->setParameter('external', $isExternal)
            ->setMaxResults(1)
        ;

        return $qb->getQuery()->execute()[0];
    }

}
