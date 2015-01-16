<?php

namespace Dizda\Bundle\AppBundle\DataFixtures\ORM;

use Dizda\Bundle\AppBundle\Entity\Identity;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadIdentityData
 * @codeCoverageIgnore
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class LoadIdentityData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $identity1 = (new Identity())
            ->setName('Identity1')
            ->setPublicKey('025cce72892434ae1115cc7a3052dcfc324296082db4aa406261ad651c173d1ab4')
            ->setKeychain($this->getReference('keychain-1'))
        ;

        $identity2 = (new Identity())
            ->setName('Identity2')
            ->setPublicKey('03bdd087f7c9d6aac4857be62dc7d80f5f9435f57293a84ed50fe689d7ade58983')
            ->setKeychain($this->getReference('keychain-1'))
        ;

        $identity3 = (new Identity())
            ->setName('Identity3')
            ->setPublicKey('03d839f849abb601f9bcd5c7527c2397a39cf57e54449c8340de32b3c6269d6005')
            ->setKeychain($this->getReference('keychain-1'))
        ;

        $manager->persist($identity1);
        $manager->persist($identity2);
        $manager->persist($identity3);
        $manager->flush();

        $this->addReference('identity-1', $identity1);
        $this->addReference('identity-2', $identity2);
        $this->addReference('identity-3', $identity3);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}
