<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Dizda\Bundle\AppBundle\Traits\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

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

    const STATUS_QUEUED     = 1; // Sent to rabbitmq, but not processed yet
    const STATUS_PROCESSED  = 2; // Processed
    const STATUS_CANCELLED  = 3; // Cancelled for X reasons

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Type("integer")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint")
     *
     * @Serializer\Exclude()
     */
    private $status;

    /**
     * @var \Dizda\Bundle\AppBundle\Entity\AddressTransaction
     *
     * @ORM\OneToOne(targetEntity="AddressTransaction", inversedBy="topup")
     * @ORM\JoinColumn(name="address_transaction_id", referencedColumnName="id", nullable=false)
     *
     * @Serializer\Type("Dizda\Bundle\AppBundle\Entity\AddressTransaction")
     **/
    private $transaction;

    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Deposit
     *
     * @ORM\ManyToOne(targetEntity="Deposit", inversedBy="topups")
     * @ORM\JoinColumn(name="deposit_id", referencedColumnName="id", nullable=false)
     *
     * @Serializer\Type("Dizda\Bundle\AppBundle\Entity\Deposit")
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

    /**
     * Set transaction
     *
     * @param \Dizda\Bundle\AppBundle\Entity\AddressTransaction $transaction
     * @return DepositTopup
     */
    public function setTransaction(\Dizda\Bundle\AppBundle\Entity\AddressTransaction $transaction)
    {
        $this->transaction = $transaction;

        return $this;
    }

    /**
     * Get transaction
     *
     * @return \Dizda\Bundle\AppBundle\Entity\AddressTransaction 
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}
