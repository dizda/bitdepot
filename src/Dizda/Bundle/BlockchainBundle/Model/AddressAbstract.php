<?php

namespace Dizda\Bundle\BlockchainBundle\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class AddressAbstract
 */
abstract class AddressAbstract
{

    /**
     * @var string
     *
     * @Serializer\Type("string")
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
     */
    protected $totalReceived;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $totalSent;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $unconfirmedBalance;

    /**
     * @var integer
     *
     * @Serializer\Type("integer")
     */
    protected $unconfirmedTxApperances;

    /**
     * @var integer
     *
     * @Serializer\Type("integer")
     */
    protected $txApperances;

    /**
     * @var array
     *
     * @Serializer\Type("array")
     */
    protected $transactions;



    /**
     * @codeCoverageIgnore
     *
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param mixed $balance
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return mixed
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param mixed $totalReceived
     */
    public function setTotalReceived($totalReceived)
    {
        $this->totalReceived = $totalReceived;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return mixed
     */
    public function getTotalReceived()
    {
        return $this->totalReceived;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param mixed $totalSent
     */
    public function setTotalSent($totalSent)
    {
        $this->totalSent = $totalSent;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return mixed
     */
    public function getTotalSent()
    {
        return $this->totalSent;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param array $transactions
     */
    public function setTransactions($transactions)
    {
        $this->transactions = $transactions;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return array
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param mixed $txApperances
     */
    public function setTxApperances($txApperances)
    {
        $this->txApperances = $txApperances;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return mixed
     */
    public function getTxApperances()
    {
        return $this->txApperances;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param mixed $unconfirmedBalance
     */
    public function setUnconfirmedBalance($unconfirmedBalance)
    {
        $this->unconfirmedBalance = $unconfirmedBalance;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return mixed
     */
    public function getUnconfirmedBalance()
    {
        return $this->unconfirmedBalance;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param mixed $unconfirmedTxApperances
     */
    public function setUnconfirmedTxApperances($unconfirmedTxApperances)
    {
        $this->unconfirmedTxApperances = $unconfirmedTxApperances;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return mixed
     */
    public function getUnconfirmedTxApperances()
    {
        return $this->unconfirmedTxApperances;
    }

}
