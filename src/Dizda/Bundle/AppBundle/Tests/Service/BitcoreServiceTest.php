<?php

namespace Dizda\Bundle\AppBundle\Tests\Service;

use Dizda\Bundle\AppBundle\Service\CallbackService;
use Dizda\Bundle\AppBundle\Tests\BaseFunctionalTestController;

/**
 * Class BitcoreServiceTest
 *
 * Test functionally both PHP code and NodeJS code to check if they interact well.
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class BitcoreServiceTest extends BaseFunctionalTestController
{
    /**
     * @var \Dizda\Bundle\AppBundle\Service\BitcoreService
     */
    private $service;

    /**
     * Transaction already broadcasted
     *
     * @group functional
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegExp /transaction already in block chain/
     */
    public function testBroadcastTransactionTransactionAlreadyInBlockChain()
    {
        $this->service->broadcastTransaction('0100000003e3d58aca306e0e810a0d7b058eb8c765edf43cce6055b81aac7c32c439ba84c601000000fc0047304402201238a0a6fbbc356f5f4e34b24538a7008b1640a6278839678a262cb98201a5fb022071ad08192f37c63d2ba8df2f40a0e86b5e42a7e9bd6d6f45526b157de6fae50a0147304402206d427b3921772032b97236b4a03768c197b996399e9679008b0cec7f3b32ea7902201b7f64c01e80657bb40bff192759637d6d96dad778b232d89c8ca55292b5c632014c695221021cdeafd9f600b77f75d6251791216ced743bfce49a6b8c350b771a68f887e42c2102a82fa18585e24ab25e4ac7eab77d0ef2a3750f989241b2d831a1c2ae9c9987a52102ceca243e88721d1d3d638e1e8b2296f37cf2786e7d45f85604ad0c5dbc28398653aeffffffff6af6ed6c989bde9feff1a250cd7c98e39546ce3a9949d0b761310fffca41980a00000000fdfe00004830450221008a38d94914367665d147327d594eab2f374bb5bd0e41e833830d4c96fed538ab02207b49484bd94f90af397594bef32bdd44ff5130ebbf85a451b2bf9087df2c929c014830450221009cdc87103f31d492a863dffe192c6edd85312ee036ee7ce7281534f75329cff102205cdbb0bd24e01e76fb9e64a4e53c4a6577c4862edef36be626a138eaee39de18014c69522102f17a50cf939e4b70b53aa39398b37358909d359a855902e90894cd1dba249c9421038828deadef536786c37ad5afbad502ba24567a653a0c7171452c517983990b532103e4e728d4f255693e332a571ceffa7bb02a339f8b8bc6ebe8bacadeac0973a51153aeffffffff69b9405fa9dcceb67cc8f13ec41d8dfb7b6a714d1cb040d13b738a1b9d7bccea01000000fdfe0000483045022100a197984099d0d496e1fa7fbdb6572843903b22326f50b91356679abcec5442bd0220074586567f8c2e02ce36c5a02a6d713baa154f12caaf8a2bb012923536048fd001483045022100e15b941bffa4f7f6539c88e712911f5f98cb5fbf481885a33422e32fb18858380220066e7fa1cb913b12605589a00beed8204d11fbc3f05f8a7bacfc5b4bd0b3c112014c695221022d78f35a74ef335bf08fe2f0acce00fa7b2bbb61e982f9d53002d883a4e5f20e2103ea01bfc06390504c9af46ad352fd85d0202ba9dfaf43ef18c2e9c7a489eb7ea02103ea2b72ff516d0d187421d7b490a90797fe0d84f6203a5bc37121a35fc67d151653aeffffffff02204e0000000000001976a914ce7a8cbb902cde4bd719622a12a395b2dd28f87788ac98f100000000000017a91437aec22c34a08973deda23f39afdf6c4419747b48700000000');
    }

    /**
     * Some evil code rather than serialized transaction
     *
     * @group functional
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Wrong raw transaction hash.
     */
    public function testBroadcastTransactionWrongRawTransactionHash()
    {
        $this->service->broadcastTransaction('coucou c moi');
    }

    /**
     * Some evil code rather than serialized transaction
     *
     * @group functional
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegExp /Generic error/
     */
    public function testBroadcastTransactionGenericError()
    {
        $this->service->broadcastTransaction('01000000013d1eb99f7956b62c9b444fa7c1bde3fd4bf877bb0f024b632cba7acbcc91864001000000fdfd0000483045022100a8fac3e7f45840a9cbec5ba7fce6e82deba7b5cf0c71b696134197a884e22dee0220373277411a58f90ea5437339f7639a6ca1879a16943da436d4b1f56bb64635f60147304402201dea1a2d412801fe985e2ef698b9b1062d1aeb936d9ba3b8eec0883c0bc3481002203b35b76f7d34b2501cf383105c9cc34c31b7e282a9b4a879787005eb46b2a3c5014c69522102650f262cb8c254c3a7f905407305b574284b2733a4fd031f11bbd22d83631b3621027fe2f813fdb70e894954ec3a0d7e043d2d82e95095c401717e6c868ea8dd942621035e66df13cf9fb9d1777c9016b9e5155a3f90fde200477afb49d052680094d7ab53aeffffffff0250a50500000000001976a9147afa489a639d3a0687505f046cdb402304143f7a88ac581b00000000000017a91447f71c733e8abba8fb443de29646b29646cc40c48700000000');
    }

    /**
     * Instantiate
     *
     * @before
     */
    public function setUpObjects()
    {
        $this->service = $this->getContainer()->get('dizda_app.service.bitcore');
    }
}
