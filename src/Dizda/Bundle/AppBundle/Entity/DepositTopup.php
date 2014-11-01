<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Dizda\Bundle\AppBundle\Traits\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * DepositTopup
 *
 * @ORM\Table(name="deposit_topup")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class DepositTopup
{
    use Timestampable;

    const STATUS_QUEUED     = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_PROCESSED  = 3;
    const STATUS_CANCELLED  = 4;

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
     * @ORM\Column(name="balance_before", type="decimal")
     */
    private $balanceBefore;

    /**
     * @var string
     *
     * @ORM\Column(name="balance_after", type="decimal")
     */
    private $balanceAfter;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal")
     */
    private $amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Deposit
     *
     * @ORM\ManyToOne(targetEntity="Deposit", inversedBy="topups")
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
     * @return DepositTopup
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
     * Set status
     *
     * @param integer $status
     * @return DepositTopup
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set balanceBefore
     *
     * @param string $balanceBefore
     * @return DepositTopup
     */
    public function setBalanceBefore($balanceBefore)
    {
        $this->balanceBefore = $balanceBefore;

        return $this;
    }

    /**
     * Get balanceBefore
     *
     * @return string 
     */
    public function getBalanceBefore()
    {
        return $this->balanceBefore;
    }

    /**
     * Set balanceAfter
     *
     * @param string $balanceAfter
     * @return DepositTopup
     */
    public function setBalanceAfter($balanceAfter)
    {
        $this->balanceAfter = $balanceAfter;

        return $this;
    }

    /**
     * Get balanceAfter
     *
     * @return string 
     */
    public function getBalanceAfter()
    {
        return $this->balanceAfter;
    }

    /**
     * Set deposit
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Deposit $deposit
     * @return DepositTopup
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
