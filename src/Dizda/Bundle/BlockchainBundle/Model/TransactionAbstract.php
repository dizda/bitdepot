<?php

namespace Dizda\Bundle\BlockchainBundle\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class TransactionAbstract
 */
abstract class TransactionAbstract
{

    /**
     * @var string
     */
    protected $txid;

    /**
     * @var integer
     */
    protected $confirmations;

    /**
     * @var array<\Dizda\Bundle\BlockchainBundle\Model\TransactionInputAbstract>
     */
    protected $inputs;

    /**
     * @var array<\Dizda\Bundle\BlockchainBundle\Model\TransactionOutputAbstract>
     */
    protected $outputs;

    /**
     * @var string
     */
    protected $fees;

    /**
     * @var string
     */
    protected $amount;

    /**
     * @codeCoverageIgnore
     *
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return int
     */
    public function getConfirmations()
    {
        return $this->confirmations;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return mixed
     */
    public function getFees()
    {
        return $this->fees;
    }

    /**
     * @return array
     */
    public function getInputs()
    {
        return $this->inputs;
    }

    /**
     * @return mixed
     */
    public function getOutputs()
    {
        return $this->outputs;
    }

    /**
     * @return mixed
     */
    public function getTxid()
    {
        return $this->txid;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param string $amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param int $confirmations
     *
     * @return $this
     */
    public function setConfirmations($confirmations)
    {
        $this->confirmations = $confirmations;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param string $fees
     *
     * @return $this
     */
    public function setFees($fees)
    {
        $this->fees = $fees;

        return $this;
    }

    /**
     * @param array $inputs
     *
     * @return $this
     */
    public function setInputs($inputs)
    {
        $this->inputs = $inputs;

        return $this;
    }

    /**
     * @param array $outputs
     *
     * @return $this
     */
    public function setOutputs($outputs)
    {
        $this->outputs = $outputs;

        return $this;
    }

    /**
     * @param string $txid
     *
     * @return $this
     */
    public function setTxid($txid)
    {
        $this->txid = $txid;

        return $this;
    }
}
