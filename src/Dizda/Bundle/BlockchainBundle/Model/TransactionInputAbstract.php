<?php

namespace Dizda\Bundle\BlockchainBundle\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class TransactionInputAbstract
 */
abstract class TransactionInputAbstract
{

    /**
     * @var string
     */
    protected $txid;

    /**
     * @var integer
     */
    protected $vout;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $address;

    /**
     * @var string
     */
    protected $doubleSpentTxID;

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getDoubleSpentTxID()
    {
        return $this->doubleSpentTxID;
    }

    /**
     * @return string
     */
    public function getTxid()
    {
        return $this->txid;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getVout()
    {
        return $this->vout;
    }
}
