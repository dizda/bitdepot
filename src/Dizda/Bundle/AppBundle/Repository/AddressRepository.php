<?php

namespace Dizda\Bundle\AppBundle\Repository;

use Dizda\Bundle\AppBundle\Entity\Address;
use Dizda\Bundle\AppBundle\Entity\Application;
use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Class AddressRepository
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class AddressRepository extends EntityRepository
{

    /**
     * @param bool $isExternal Internal or External address is given
     *
     * @return Address
     * @deprecated Use getLastDerivation() instead
     */
    public function getOneFreeAddress($isExternal = true)
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.deposit', 'd')
            ->leftJoin('a.withdrawChangeAddress', 'wca')
            ->andWhere('d.id is NULL')   // Where not used for deposits yet
            ->andWhere('wca.id is NULL') // Where not used as a change address yet
            ->andWhere('a.isExternal = :external')
            // TODO: where application id || keychain
            ->setParameter('external', $isExternal)
            ->setMaxResults(1)
        ;

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @param array $filters
     *
     * @return Pagerfanta
     */
    public function getAddresses(array $filters)
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.deposit', 'd')
            ->leftJoin('a.withdrawChangeAddress', 'wca')
            ->leftJoin('a.transactions', 't')
            ->orderBy('a.createdAt', 'desc')
            // TODO: where application id || keychain
//            ->setMaxResults(25)
        ;

        if ($filters['show'] === 'only_used') {
            // Shows addresses only used for deposit OR withdrawChange OR with some transactions
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->isNotNull('d.id'),
                    $qb->expr()->isNotNull('wca.id'),
                    $qb->expr()->isNotNull('t.id')
                )
            );
        } elseif ($filters['show'] === 'positive_balance') {
            $qb->andWhere('a.balance > 0');
        }

        $pagerfanta = (new Pagerfanta(new DoctrineORMAdapter($qb)))
            ->setMaxPerPage($filters['maxPerPage'])
            ->setCurrentPage($filters['currentPage'])
        ;

        return $pagerfanta;
    }

    /**
     * @param Application $application
     * @param bool $isExternal
     *
     * @return Address|null
     */
    public function getLastDerivation(Application $application, $isExternal = true)
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.application = :application')
            ->andWhere('a.isExternal = :isExternal')
            ->setParameter('application', $application)
            ->setParameter('isExternal', $isExternal)
            ->orderBy('a.derivation', 'DESC')
            ->setMaxResults(1)
        ;

        $address = $qb->getQuery()->getOneOrNullResult();

        return $address;
    }
}
