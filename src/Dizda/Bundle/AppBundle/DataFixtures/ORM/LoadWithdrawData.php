<?php

namespace Dizda\Bundle\AppBundle\DataFixtures\ORM;

use Dizda\Bundle\AppBundle\Entity\Withdraw;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadWithdrawData
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class LoadWithdrawData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $withdraw = (new Withdraw())
            ->setKeychain($this->getReference('keychain-1'))
            ->setRawTransaction('010000000155a3dd66c03bd64f6512fc47c9156db1a431946e07536d7ed5321df051d6bfc50100000000ffffffff0210270000000000001976a914d356d4d8079f8556be4c8102ecf00cc63344e75488ac102700000000000017a914dec137068316fe8ddffdf05befb24d786e38adf98700000000')
            ->setRawSignedTransaction('010000000155a3dd66c03bd64f6512fc47c9156db1a431946e07536d7ed5321df051d6bfc501000000fdfd0000483045022100a2054c60df4d2cad65b2a3970ec20c924c487389115ed5bced7dd684e353c78c02202d142f84317d4a26bb41542f5935068f28a59f48aa09f5bd84a7e4798220ea5b0147304402200c8abff78912bcfa787941d206b00ace246da11841f6ae39ebcd5fa42902825e02200223df3d31a4ed998439dda54d4370592c7ac188e64aa2e0efcd916146554ffb014c69522102a71ef05b31072d778b35f47d6204b80733db964498267f61dec2bdaaca22752121025bcd11b34f89704aba4d8f88d5e4d5db2a65ed1d6aabbc1f335f2eec771ee4e421024da9e2fb260317954c54df92d78051ef230de9a5aafef2592bb3b4f666209bcf53aeffffffff0210270000000000001976a914d356d4d8079f8556be4c8102ecf00cc63344e75488ac102700000000000017a914dec137068316fe8ddffdf05befb24d786e38adf98700000000')
            ->setChangeAddressAmount('0.0001')
            ->setFees('0.0001')
            ->setChangeAddress($this->getReference('address-5'))
        ;

        $withdraw->setInputs([ $this->getReference('transaction-1') ]);
        $withdraw->setOutputs([ $this->getReference('withdraw-output-1') ]);

        $withdraw->withdrawed('431c5231114ce2d00125ea4a88f4e4637b80fef1117a0b20606204e45cc3678f');

        $manager->persist($withdraw);
        $manager->flush();

        $this->addReference('withdraw-1', $withdraw);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 8; // the order in which fixtures will be loaded
    }
}