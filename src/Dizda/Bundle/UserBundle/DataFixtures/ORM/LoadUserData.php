<?php

namespace Dizda\Bundle\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Dizda\Bundle\UserBundle\Entity\User;

/**
 * Class LoadUserData
 * @codeCoverageIgnore
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('dizda');
        $userAdmin->setEmail('dizda@dizda.fr');
        $userAdmin->setPlainPassword('bambou');
        $userAdmin->setEnabled(true);

        $manager->persist($userAdmin);
        $manager->flush();

        $this->addReference('user-admin', $userAdmin);
    }


    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 9; // the order in which fixtures will be loaded
    }
}