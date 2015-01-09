<?php

namespace Dizda\Bundle\AppBundle\Tests;

/**
 * Class Fixtures
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class Fixtures
{

    /**
     * Return namespaces of fixtures to load
     *
     * @return array
     */
    public static function getPaths()
    {
        return [
            'Dizda\Bundle\AppBundle\DataFixtures\ORM\LoadKeychainData',
            'Dizda\Bundle\AppBundle\DataFixtures\ORM\LoadIdentityData',
            'Dizda\Bundle\AppBundle\DataFixtures\ORM\LoadApplicationData',
            'Dizda\Bundle\AppBundle\DataFixtures\ORM\LoadPubKeyData',
            'Dizda\Bundle\AppBundle\DataFixtures\ORM\LoadAddressData',
            'Dizda\Bundle\AppBundle\DataFixtures\ORM\LoadDepositData',
            'Dizda\Bundle\AppBundle\DataFixtures\ORM\LoadAddressTransactionData',
            'Dizda\Bundle\AppBundle\DataFixtures\ORM\LoadWithdrawOutputData',
            'Dizda\Bundle\AppBundle\DataFixtures\ORM\LoadWithdrawData',
            'Dizda\Bundle\UserBundle\DataFixtures\ORM\LoadUserData'
        ];
    }

}
