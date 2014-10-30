<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DepositTransaction
 *
 * @ORM\Table(name="deposit_transaction")
 * @ORM\Entity
 */
class DepositTransaction
{
    use \Dizda\Bundle\AppBundle\Traits\Timestampable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=16, scale=8, nullable=false)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="from_address", type="string", length=255)
     */
    private $fromAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="tx_id", type="string", length=255)
     */
    private $txId;

    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Deposit
     *
     * @ORM\ManyToOne(targetEntity="Deposit", inversedBy="transactions")
     * @ORM\JoinColumn(name="deposit_id", referencedColumnName="id", nullable=false)
     */
    private $deposit;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set amount
     *
     * @param string $amount
     * @return DepositTransaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set fromAddress
     *
     * @param string $fromAddress
     * @return DepositTransaction
     */
    public function setFromAddress($fromAddress)
    {
        $this->fromAddress = $fromAddress;

        return $this;
    }

    /**
     * Get fromAddress
     *
     * @return string 
     */
    public function getFromAddress()
    {
        return $this->fromAddress;
    }

    /**
     * Set txId
     *
     * @param string $txId
     * @return DepositTransaction
     */
    public function setTxId($txId)
    {
        $this->txId = $txId;

        return $this;
    }

    /**
     * Get txId
     *
     * @return string 
     */
    public function getTxId()
    {
        return $this->txId;
    }

    /**
     * Set deposit
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Deposit $deposit
     * @return DepositTransaction
     */
    public function setDeposit(\Dizda\Bundle\AppBundle\Entity\Deposit $deposit)
    {
        $this->deposit = $deposit;

        return $this;
    }

    /**
     * Get deposit
     *
     * @return \Dizda\Bundle\AppBundle\Entity\Deposit 
     */
    public function getDeposit()
    {
        return $this->deposit;
    }
}
