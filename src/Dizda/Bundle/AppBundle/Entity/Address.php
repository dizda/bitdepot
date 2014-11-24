<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Address
 *
 * @ORM\Table(name="address")
 * @ORM\Entity(repositoryClass="Dizda\Bundle\AppBundle\Repository\AddressRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Address
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
     * The address
     *
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_external", type="boolean")
     *
     * @Serializer\Groups({"WithdrawDetail"})
     */
    private $isExternal;

    /**
     * @var integer
     *
     * @ORM\Column(name="derivation", type="integer")
     *
     * @Serializer\Groups({"WithdrawDetail"})
     */
    private $derivation;

    /**
     * @var string
     *
     * @ORM\Column(name="balance", type="decimal", precision=16, scale=8, nullable=false)
     *
     * @Serializer\Groups("Deposits")
     * @Serializer\Type("string")
     */
    private $balance;

    /**
     * @var string
     *
     * @ORM\Column(name="redeem_script", type="text", nullable=false)
     *
     * @Serializer\Groups({"WithdrawDetail"})
     */
    private $redeemScript;

    /**
     * @var string
     *
     * @ORM\Column(name="script_pub_key", type="string", nullable=false)
     */
    private $scriptPubKey;

    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Keychain
     *
     * @ORM\ManyToOne(targetEntity="Dizda\Bundle\AppBundle\Entity\Keychain", inversedBy="addresses")
     * @ORM\JoinColumn(name="keychain_id", referencedColumnName="id", nullable=false)
     *
     * @Serializer\Exclude
     */
    private $keychain;

    /**
     * @ORM\OneToOne(targetEntity="Deposit", mappedBy="addressExternal")
     *
     * @Serializer\Exclude
     **/
    private $deposit;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity  = "AddressTransaction",
     *      mappedBy      = "address"
     * )
     *
     * @Serializer\Exclude
     */
    private $transactions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->transactions = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set value
     *
     * @param string $value
     * @return Address
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set isExternal
     *
     * @param boolean $isExternal
     * @return Address
     */
    public function setIsExternal($isExternal)
    {
        $this->isExternal = $isExternal;

        return $this;
    }

    /**
     * Get isExternal
     *
     * @return boolean 
     */
    public function getIsExternal()
    {
        return $this->isExternal;
    }

    /**
     * Set derivation
     *
     * @param integer $derivation
     * @return Address
     */
    public function setDerivation($derivation)
    {
        $this->derivation = $derivation;

        return $this;
    }

    /**
     * Get derivation
     *
     * @return integer 
     */
    public function getDerivation()
    {
        return $this->derivation;
    }

    /**
     * Set balance
     *
     * @param string $balance
     * @return Address
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return string 
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set keychain
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Keychain $keychain
     * @return Address
     */
    public function setKeychain(\Dizda\Bundle\AppBundle\Entity\Keychain $keychain)
    {
        $this->keychain = $keychain;

        return $this;
    }

    /**
     * Get keychain
     *
     * @return \Dizda\Bundle\AppBundle\Entity\Keychain 
     */
    public function getKeychain()
    {
        return $this->keychain;
    }

    /**
     * Set deposit
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Deposit $deposit
     * @return Address
     */
    public function setDeposit(\Dizda\Bundle\AppBundle\Entity\Deposit $deposit = null)
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
     * Add transactions
     *
     * @param \Dizda\Bundle\AppBundle\Entity\AddressTransaction $transactions
     * @return Address
     */
    public function addTransaction(\Dizda\Bundle\AppBundle\Entity\AddressTransaction $transactions)
    {
        $this->transactions[] = $transactions;

        return $this;
    }

    /**
     * Remove transactions
     *
     * @param \Dizda\Bundle\AppBundle\Entity\AddressTransaction $transactions
     */
    public function removeTransaction(\Dizda\Bundle\AppBundle\Entity\AddressTransaction $transactions)
    {
        $this->transactions->removeElement($transactions);
    }

    /**
     * Get transactions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * @param string $txid
     *
     * @return bool
     */
    public function hasTransaction($txid)
    {
        foreach ($this->transactions as $transaction) {
            if ($transaction->getId() === $txid) {
                return true;
            }
        }

        return false;
    }

    /**
     * Set redeemScript
     *
     * @param string $redeemScript
     * @return Address
     */
    public function setRedeemScript($redeemScript)
    {
        $this->redeemScript = $redeemScript;

        return $this;
    }

    /**
     * Get redeemScript
     *
     * @return string 
     */
    public function getRedeemScript()
    {
        return $this->redeemScript;
    }

    /**
     * Set scriptPubKey
     *
     * @param string $scriptPubKey
     * @return Address
     */
    public function setScriptPubKey($scriptPubKey)
    {
        $this->scriptPubKey = $scriptPubKey;

        return $this;
    }

    /**
     * Get scriptPubKey
     *
     * @return string 
     */
    public function getScriptPubKey()
    {
        return $this->scriptPubKey;
    }
}
