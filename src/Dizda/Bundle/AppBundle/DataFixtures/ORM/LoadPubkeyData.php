<?php

namespace Dizda\Bundle\AppBundle\DataFixtures\ORM;

use Dizda\Bundle\AppBundle\Entity\PubKey;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadPubKeyData
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class LoadPubKeyData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $pubkey1 = (new PubKey())
            ->setName('PubKey1 keychain1 fixture')
            ->setExtendedPubKey('xpub6DAXwftEkosDZopbbVQ2me4j9R5FHYq1H7qabk3mPLBbhxHBVFLkid5beJUmzj6XJcvRskt544uCHFc4LFXkeyYCpAWzPTLUPBTT5ZifPgn')
            ->setApplication($this->getReference('application-1'))
        ;

        $pubkey2 = (new PubKey())
            ->setName('PubKey2 keychain1 fixture')
            ->setExtendedPubKey('xpub6D2a4Mq3EypbBVRPXFnphXWRMfmVkgRk2Y5ZuFZ5SrvUqci9vc4zMroUxgNYoe9x6fjrFSK6LnC4yg1xFZS4JrEFQ4s14kHXeJwARbCQg9r')
            ->setApplication($this->getReference('application-1'))
        ;

        $pubkey3 = (new PubKey())
            ->setName('PubKey3 keychain1 fixture')
            ->setExtendedPubKey('xpub6CceUL4HSiPa2ms86Ay94Pw4YETv4xAyfRyt7vk9CuQ71rZo5hmNmm9uSx7vVxVG4SUeADNBSUhoRNGU2CbPEsGxNtBJ7uBSQQUdxtSNeT2')
            ->setApplication($this->getReference('application-1'))
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
        return 3; // the order in which fixtures will be loaded
    }
}