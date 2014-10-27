<?php

namespace Dizda\Bundle\BlockchainBundle\Model\Insight;

use Dizda\Bundle\BlockchainBundle\Model\AddressAbstract;
use JMS\Serializer\Annotation as Serializer;

class Address extends AddressAbstract
{
    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("addrStr")
     */
    protected $address;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $balance;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("totalReceived")
     */
    protected $totalReceived;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("totalSent")
     */
    protected $totalSent;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("unconfirmedBalance")
     */
    protected $unconfirmedBalance;

    /**
     * @var string
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("unconfirmedTxApperances")
     */
    protected $unconfirmedTxApperances;

    /**
     * @var string
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("txApperances")
     */
    protected $txApperances;

    /**
     * @var string
     *
     * @Serializer\Type("array")
     */
    protected $transactions;

}