<?php

namespace Dizda\Bundle\AppBundle\DataFixtures\ORM;

use Dizda\Bundle\AppBundle\Entity\Address;
use Dizda\Bundle\AppBundle\Entity\Keychain;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadKeychainData
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class LoadKeychainData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $keychain = (new Keychain())
            ->setName('Keychain Fixture')
            ->setSignRequired(2)
            ->setGroupWithdrawsByQuantity(1)
        ;

        $manager->persist($keychain);
        $manager->flush();

        $this->addReference('keychain-1', $keychain);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}