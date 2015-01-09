<?php

namespace Dizda\Bundle\AppBundle\DataFixtures\ORM;

use Dizda\Bundle\AppBundle\Entity\Address;
use Dizda\Bundle\AppBundle\Entity\WithdrawOutput;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadWithdrawOutputData
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class LoadWithdrawOutputData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        # For Withdraw #1
        $withdrawOutput1 = (new WithdrawOutput())
            ->setApplication($this->getReference('application-1'))
            ->setAmount('0.0001')
            ->setIsAccepted(true)
            ->setToAddress('1LGTbdVSEbD9C37qXcpvVJ1egdBu8jYSeV')
        ;

        # For Withdraw #2
        $withdrawOutput2 = (new WithdrawOutput())
            ->setApplication($this->getReference('application-1'))
            ->setAmount('0.0001')
            ->setIsAccepted(true)
            ->setToAddress('1MxXHgScDGaA7GaJY8bGa9MsCKU6iXaiRh')
        ;

        $manager->persist($withdrawOutput1);
        $manager->persist($withdrawOutput2);
        $manager->flush();

        $this->addReference('withdraw-output-1', $withdrawOutput1);
        $this->addReference('withdraw-output-2', $withdrawOutput2);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 8; // the order in which fixtures will be loaded
    }
}