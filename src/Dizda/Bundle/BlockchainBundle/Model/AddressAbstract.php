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
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $balance
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param mixed $totalReceived
     */
    public function setTotalReceived($totalReceived)
    {
        $this->totalReceived = $totalReceived;
    }

    /**
     * @return mixed
     */
    public function getTotalReceived()
    {
        return $this->totalReceived;
    }

    /**
     * @param mixed $totalSent
     */
    public function setTotalSent($totalSent)
    {
        $this->totalSent = $totalSent;
    }

    /**
     * @return mixed
     */
    public function getTotalSent()
    {
        return $this->totalSent;
    }

    /**
     * @param array $transactions
     */
    public function setTransactions($transactions)
    {
        $this->transactions = $transactions;
    }

    /**
     * @return array
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * @param mixed $txApperances
     */
    public function setTxApperances($txApperances)
    {
        $this->txApperances = $txApperances;
    }

    /**
     * @return mixed
     */
    public function getTxApperances()
    {
        return $this->txApperances;
    }

    /**
     * @param mixed $unconfirmedBalance
     */
    public function setUnconfirmedBalance($unconfirmedBalance)
    {
        $this->unconfirmedBalance = $unconfirmedBalance;
    }

    /**
     * @return mixed
     */
    public function getUnconfirmedBalance()
    {
        return $this->unconfirmedBalance;
    }

    /**
     * @param mixed $unconfirmedTxApperances
     */
    public function setUnconfirmedTxApperances($unconfirmedTxApperances)
    {
        $this->unconfirmedTxApperances = $unconfirmedTxApperances;
    }

    /**
     * @return mixed
     */
    public function getUnconfirmedTxApperances()
    {
        return $this->unconfirmedTxApperances;
    }

}
