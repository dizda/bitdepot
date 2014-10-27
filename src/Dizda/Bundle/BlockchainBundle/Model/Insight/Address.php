<?php

namespace Dizda\Bundle\BlockchainBundle\Model\Insight;

use JMS\Serializer\Annotation as Serializer;

class Address
{

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("addrStr")
     */
    private $addrStr;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    private $balance;

    /**
     * @var string
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("balanceSat")
     */
    private $balanceSat;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("totalReceived")
     */
    private $totalReceived;

    /**
     * @var string
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("totalReceivedSat")
     */
    private $totalReceivedSat;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("totalSent")
     */
    private $totalSent;

    /**
     * @var string
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("totalSentSat")
     */
    private $totalSentSat;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("unconfirmedBalance")
     */
    private $unconfirmedBalance;

    /**
     * @var string
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("unconfirmedBalanceSat")
     */
    private $unconfirmedBalanceSat;

    /**
     * @var string
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("unconfirmedTxApperances")
     */
    private $unconfirmedTxApperances;

    /**
     * @var string
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("txApperances")
     */
    private $txApperances;

    /**
     * @var string
     *
     * @Serializer\Type("array")
     */
    private $transactions;

}