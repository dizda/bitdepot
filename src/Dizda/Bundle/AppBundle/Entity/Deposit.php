<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Dizda\Bundle\AppBundle\Traits\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Deposit
 *
 * @ORM\Table(name="deposit")
 * @ORM\Entity(repositoryClass="Dizda\Bundle\AppBundle\Repository\DepositRepository")
 */
class Deposit
{
    const TYPE_AMOUNT_EXPECTED = 1;
    const TYPE_AMOUNT_UNKNOWN  = 2;

    use Timestampable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="amount_expected", type="decimal", precision=16, scale=8, nullable=true)
     */
    private $amountExpected;

    /**
     * @var string
     *
     * @ORM\Column(name="amount_filled", type="decimal", precision=16, scale=8, nullable=false, options={"default"=0})
     */
    private $amountFilled = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_fulfilled", type="boolean")
     */
    private $isFulfilled;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expires_at", type="datetime", nullable=true)
     */
    private $expiresAt;

    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Application
     *
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="deposits")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id", nullable=false)
     */
    private $application;

    /**
     * @ORM\OneToOne(targetEntity="Address", inversedBy="deposit")
     * @ORM\JoinColumn(name="address_external_id", referencedColumnName="id", nullable=false)
     **/
    private $addressExternal;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity    = "DepositTransaction",
     *      mappedBy        = "deposit"
     * )
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
     * Set type
     *
     * @param integer $type
     * @return Deposit
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
     * Set amountExpected
     *
     * @param string $amountExpected
     * @return Deposit
     */
    public function setAmountExpected($amountExpected)
    {
        $this->amountExpected = $amountExpected;

        return $this;
    }

    /**
     * Get amountExpected
     *
     * @return string 
     */
    public function getAmountExpected()
    {
        return $this->amountExpected;
    }

    /**
     * Set amountFilled
     *
     * @param string $amountFilled
     * @return Deposit
     */
    public function setAmountFilled($amountFilled)
    {
        $this->amountFilled = $amountFilled;

        return $this;
    }

    /**
     * Get amountFilled
     *
     * @return string 
     */
    public function getAmountFilled()
    {
        return $this->amountFilled;
    }

    /**
     * Set isFulfilled
     *
     * @param boolean $isFulfilled
     * @return Deposit
     */
    public function setIsFulfilled($isFulfilled)
    {
        $this->isFulfilled = $isFulfilled;

        return $this;
    }

    /**
     * Get isFulfilled
     *
     * @return boolean 
     */
    public function getIsFulfilled()
    {
        return $this->isFulfilled;
    }

    /**
     * Set expiresAt
     *
     * @param \DateTime $expiresAt
     * @return Deposit
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Get expiresAt
     *
     * @return \DateTime 
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Set addressExternal
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Address $addressExternal
     * @return Deposit
     */
    public function setAddressExternal(\Dizda\Bundle\AppBundle\Entity\Address $addressExternal = null)
    {
        $this->addressExternal = $addressExternal;

        return $this;
    }

    /**
     * Get addressExternal
     *
     * @return \Dizda\Bundle\AppBundle\Entity\Address
     */
    public function getAddressExternal()
    {
        return $this->addressExternal;
    }

    /**
     * Set application
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Application $application
     * @return Deposit
     */
    public function setApplication(\Dizda\Bundle\AppBundle\Entity\Application $application)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * Get application
     *
     * @return \Dizda\Bundle\AppBundle\Entity\Application 
     */
    public function getApplication()
    {
        return $this->application;
    }


    /**
     * Add transactions
     *
     * @param \Dizda\Bundle\AppBundle\Entity\DepositTransaction $transactions
     * @return Deposit
     */
    public function addTransaction(\Dizda\Bundle\AppBundle\Entity\DepositTransaction $transactions)
    {
        $this->transactions[] = $transactions;

        return $this;
    }

    /**
     * Remove transactions
     *
     * @param \Dizda\Bundle\AppBundle\Entity\DepositTransaction $transactions
     */
    public function removeTransaction(\Dizda\Bundle\AppBundle\Entity\DepositTransaction $transactions)
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
}
