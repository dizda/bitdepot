<?php

namespace Dizda\Bundle\AppBundle\DataFixtures\ORM;

use Dizda\Bundle\AppBundle\Entity\Transaction;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadTransactionData
 * @codeCoverageIgnore
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class LoadTransactionData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // For withdraw #1
        $transaction1 = (new Transaction())
            ->setType(Transaction::TYPE_IN)
            ->setAddress($this->getReference('address-4'))
            ->setAmount('0.0003')
            ->setIndex(1)
            ->setIsSpent(false)
            ->setTxid('be5282178cacb7a04696963de62f674bef9a4510f7577d21585442c1eb8e8f2f')
            ->setAddresses([ $this->getReference('address-4')->getValue() ])
        ;

        // For withdraw #2
        $transaction2 = (new Transaction())
            ->setType(Transaction::TYPE_IN)
            ->setAddress($this->getReference('address-5'))
            ->setAmount('0.0003')
            ->setIndex(0)
            ->setIsSpent(false)
            ->setTxid('997c80046cb35d37752d227d414e966e6e52af577bbd3b660f9a3a77071a928c')
            ->setAddresses([ $this->getReference('address-5')->getValue() ])
        ;

        $manager->persist($transaction1);
        $manager->persist($transaction2);
        $manager->flush();

        $this->addReference('transaction-1', $transaction1);
        $this->addReference('transaction-2', $transaction2);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 7; // the order in which fixtures will be loaded
    }
}