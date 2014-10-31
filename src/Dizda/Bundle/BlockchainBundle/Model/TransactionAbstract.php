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
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getConfirmations()
    {
        return $this->confirmations;
    }

    /**
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

}
