<?php

namespace Dizda\Bundle\AppBundle\DataFixtures\ORM;

use Dizda\Bundle\AppBundle\Entity\Pubkey;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadPubkeyData
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class LoadPubkeyData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $pubkey1 = (new Pubkey())
            ->setName('Pubkey1 keychain1 fixture')
            ->setValue('02dfe3572ad679394934e0998c9dce49548cae55e923c655f4c46d7ebf9d67eab2')
            ->setKeychain($this->getReference('keychain-1'))
        ;

        $pubkey2 = (new Pubkey())
            ->setName('Pubkey2 keychain1 fixture')
            ->setValue('024929ebd103ec6ffaafcafd19806b3a404de9dcb6231d86bf1f9dbec23cd6059b')
            ->setKeychain($this->getReference('keychain-1'))
        ;

        $pubkey3 = (new Pubkey())
            ->setName('Pubkey3 keychain1 fixture')
            ->setValue('020dcc43a616f162b0dacc2ac8f632f95b85cef52ed903ea576b127e22eef56a70')
            ->setKeychain($this->getReference('keychain-1'))
        ;

        $manager->persist($pubkey1);
        $manager->persist($pubkey2);
        $manager->persist($pubkey3);
        $manager->flush();

        $this->addReference('pubkey-1', $pubkey1);
        $this->addReference('pubkey-2', $pubkey2);
        $this->addReference('pubkey-3', $pubkey3);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}