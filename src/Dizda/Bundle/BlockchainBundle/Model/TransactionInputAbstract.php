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
    protected $index;

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
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function getDoubleSpentTxID()
    {
        return $this->doubleSpentTxID;
    }

    /**
     * @codeCoverageIgnore
     *
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
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param string $address
     *
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param string $doubleSpentTxID
     *
     * @return $this
     */
    public function setDoubleSpentTxID($doubleSpentTxID)
    {
        $this->doubleSpentTxID = $doubleSpentTxID;

        return $this;
    }

    /**
     * @param int $index
     *
     * @return $this
     */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param string $txid
     *
     * @return $this
     */
    public function setTxid($txid)
    {
        $this->txid = $txid;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
