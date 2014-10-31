<?php

namespace Dizda\Bundle\BlockchainBundle\Model\Insight;

use Dizda\Bundle\BlockchainBundle\Model\TransactionAbstract;
use JMS\Serializer\Annotation as Serializer;

class Transaction extends TransactionAbstract
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
     * @Serializer\SerializedName("confirmations")
     */
    protected $confirmations;

    /**
     * @var array<\Dizda\Bundle\BlockchainBundle\Model\TransactionInputAbstract>
     *
     * @Serializer\Type("array<Dizda\Bundle\BlockchainBundle\Model\Insight\TransactionInput>")
     * @Serializer\SerializedName("vin")
     */
    protected $inputs;

    /**
     * @var array<\Dizda\Bundle\BlockchainBundle\Model\TransactionOutputAbstract>
     *
     * @Serializer\Type("array<Dizda\Bundle\BlockchainBundle\Model\Insight\TransactionOutput>")
     * @Serializer\SerializedName("vout")
     */
    protected $outputs;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("fees")
     */
    protected $fees;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("valueOut")
     */
    protected $amount;

}
