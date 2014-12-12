<?php

namespace Dizda\Bundle\AppBundle\DataFixtures\ORM;

use Dizda\Bundle\AppBundle\Entity\Address;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadAddressData
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class LoadAddressData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $address1 = (new Address())
            ->setValue('3M2C54k8xit7oLAgSat5PbmAtbhCyp5EqU')
            ->setIsExternal(true)
            ->setDerivation(1)
            ->setBalance('0')
            ->setRedeemScript('52210244d61e612701fbefe46feb51ced989473a1c83233aaaca16c27a4fa8511df459210364759d648cbf406eed9b68d01c7cc7ebbdadb966472b042c8fcf538505ba954221021bb3d58673ba887980a91d4a56fdeaaf263f500e3db581bec3140b684895a9df53ae')
            ->setScriptPubKey('a914d40ac36da2473ef892ebe5223ab928824acf735487')
            ->setKeychain($this->getReference('keychain-1'))
        ;

        $manager->persist($address1);
        $manager->flush();

        $this->addReference('address-1', $address1);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
}