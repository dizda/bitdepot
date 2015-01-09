<?php

namespace Dizda\Bundle\AppBundle\DataFixtures\ORM;

use Dizda\Bundle\AppBundle\Entity\Deposit;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadDepositData
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class LoadDepositData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $deposit1 = (new Deposit())
            ->setAddressExternal($this->getReference('address-1'))
            ->setApplication($this->getReference('application-1'))
            ->setType(Deposit::TYPE_AMOUNT_EXPECTED)
            ->setAmountExpected('0.0002')
            ->setAmountFilled('0')
            ->setIsFulfilled(false)
        ;

        $manager->persist($deposit1);
        $manager->flush();

        $this->addReference('deposit-1', $deposit1);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 6; // the order in which fixtures will be loaded
    }
}