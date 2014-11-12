<?php

namespace Dizda\Bundle\BlockchainBundle\Model\Insight;

use Dizda\Bundle\BlockchainBundle\Model\TransactionInputAbstract;
use JMS\Serializer\Annotation as Serializer;

class TransactionInput extends TransactionInputAbstract
{

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("txid")
     */
    protected $txid;

    /**
     * @var integer
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("vout")
     */
    protected $index;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("value")
     */
    protected $value;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("addr")
     */
    protected $address;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("doubleSpentTxID")
     */
    protected $doubleSpentTxID;

}
