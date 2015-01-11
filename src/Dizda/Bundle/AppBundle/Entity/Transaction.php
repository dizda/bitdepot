<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * DepositTransaction
 *
 * @ORM\Table(name="transaction")
 * @ORM\Entity(repositoryClass="Dizda\Bundle\AppBundle\Repository\TransactionRepository")
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Transaction
{
    use \Dizda\Bundle\AppBundle\Traits\Timestampable;

    const TYPE_IN  = 1;
    const TYPE_OUT = 2;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Groups({"Deposit", "Withdraw", "WithdrawDetail"})
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="txid", type="string", nullable=false)
     *
     * @Serializer\Groups({"Deposit", "Withdraw", "WithdrawDetail"})
     * @Serializer\SerializedName("txid")
     */
    private $txid;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint")
     *
     * @Serializer\Groups({"Deposit"})
     * @Serializer\Type("integer")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=16, scale=8, nullable=false)
     *
     * @Serializer\Groups({"Deposit"})
     * @Serializer\Type("string")
     */
    private $amount;

    /**
     * The transaction index
     *
     * @var integer
     *
     * @ORM\Column(name="transaction_index", type="smallint")
     *
     * @Serializer\Exclude()
     */
    private $index;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_spent", type="boolean")
     *
     * @Serializer\Groups({"Deposit"})
     */
    private $isSpent = false;

    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Address
     *
     * @ORM\ManyToMany(targetEntity="Address", mappedBy="transactions")
     *
     * @Serializer\Type("Dizda\Bundle\AppBundle\Entity\Address")
     * @Serializer\Groups({"WithdrawDetail"})
     */
    private $addresses;

    /**
     * @ORM\OneToOne(targetEntity="DepositTopup", mappedBy="transaction")
     *
     * @Serializer\Exclude
     **/
    private $topup;

    /**
     * WithdrawInputs
     *
     * @var \Dizda\Bundle\AppBundle\Entity\Deposit
     *
     * @ORM\ManyToOne(targetEntity="Withdraw", inversedBy="withdrawInputs")
     * @ORM\JoinColumn(name="withdraw_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     *
     * @Serializer\Exclude
     */
    private $withdraw;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->addresses = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     * @codeCoverageIgnore
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
     * @return Transaction
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
     * Set id
     * @codeCoverageIgnore
     *
     * @param string $id
     * @return Transaction
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Transaction
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set topup
     * @codeCoverageIgnore
     *
     * @param \Dizda\Bundle\AppBundle\Entity\DepositTopup $topup
     * @return Transaction
     */
    public function setTopup(\Dizda\Bundle\AppBundle\Entity\DepositTopup $topup = null)
    {
        $this->topup = $topup;

        return $this;
    }

    /**
     * Get topup
     * @codeCoverageIgnore
     *
     * @return \Dizda\Bundle\AppBundle\Entity\DepositTopup
     */
    public function getTopup()
    {
        return $this->topup;
    }

    /**
     * Set isSpent
     *
     * @param boolean $isSpent
     * @return Transaction
     */
    public function setIsSpent($isSpent)
    {
        $this->isSpent = $isSpent;

        return $this;
    }

    /**
     * Get isSpent
     *
     * @return boolean
     */
    public function getIsSpent()
    {
        return $this->isSpent;
    }

    /**
     * Set withdraw
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Withdraw $withdraw
     *
     * @return Transaction
     */
    public function setWithdraw(\Dizda\Bundle\AppBundle\Entity\Withdraw $withdraw = null)
    {
        $this->withdraw = $withdraw;

        return $this;
    }

    /**
     * Get withdraw
     * @codeCoverageIgnore
     *
     * @return \Dizda\Bundle\AppBundle\Entity\Withdraw
     */
    public function getWithdraw()
    {
        return $this->withdraw;
    }

    /**
     * Set index
     *
     * @param integer $index
     * @return Transaction
     */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * Get index
     *
     * @return integer
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set txid
     *
     * @param string $txid
     * @return Transaction
     */
    public function setTxid($txid)
    {
        $this->txid = $txid;

        return $this;
    }

    /**
     * Get txid
     *
     * @return string
     */
    public function getTxid()
    {
        return $this->txid;
    }

    /**
     * Add addresses
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Address $addresses
     * @return Transaction
     */
    public function addAddress(\Dizda\Bundle\AppBundle\Entity\Address $addresses)
    {
        $addresses->addTransaction($this);
        $this->addresses[] = $addresses;

        return $this;
    }

    /**
     * Remove addresses
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Address $addresses
     */
    public function removeAddress(\Dizda\Bundle\AppBundle\Entity\Address $addresses)
    {
        $this->addresses->removeElement($addresses);
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Mark that the transaction is spent
     *
     * @return $this
     */
    public function markAsSpent()
    {
        $this->isSpent = true;

        return $this;
    }
}
