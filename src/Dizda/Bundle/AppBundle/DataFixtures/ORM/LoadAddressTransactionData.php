<?php

namespace Dizda\Bundle\AppBundle\DataFixtures\ORM;

use Dizda\Bundle\AppBundle\Entity\AddressTransaction;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadAddressTransactionData
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class LoadAddressTransactionData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $transaction = (new AddressTransaction())
            ->setType(AddressTransaction::TYPE_IN)
            ->setAddress($this->getReference('address-4'))
            ->setAmount('0.0003')
            ->setIndex(1)
            ->setIsSpent(false)
            ->setTxid('be5282178cacb7a04696963de62f674bef9a4510f7577d21585442c1eb8e8f2f')
            ->setAddresses([ $this->getReference('address-4')->getValue() ])
        ;

        $manager->persist($transaction);
        $manager->flush();

        $this->addReference('transaction-1', $transaction);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 6; // the order in which fixtures will be loaded
    }
}