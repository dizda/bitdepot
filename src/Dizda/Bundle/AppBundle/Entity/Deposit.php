<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Dizda\Bundle\AppBundle\Traits\MessageQueuing;
use Dizda\Bundle\AppBundle\Traits\MessageQueuingInterface;
use Dizda\Bundle\AppBundle\Traits\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Deposit
 *
 * @ORM\Table(name="deposit")
 * @ORM\Entity(repositoryClass="Dizda\Bundle\AppBundle\Repository\DepositRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Deposit implements MessageQueuingInterface
{
    const TYPE_AMOUNT_EXPECTED = 1;
    const TYPE_AMOUNT_TOPUP    = 2;

    use Timestampable;
    use MessageQueuing;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Groups({"Deposits", "DepositCallback"})
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint")
     *
     * @Serializer\Groups({"Deposits", "DepositCallback"})
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="amount_expected", type="decimal", precision=16, scale=8, nullable=true)
     *
     * @Serializer\Groups({"Deposits", "DepositCallback"})
     * @Serializer\Type("string")
     */
    private $amountExpected;

    /**
     * @var string
     *
     * @ORM\Column(name="amount_filled", type="decimal", precision=16, scale=8, nullable=false, options={"default"=0})
     *
     * @Serializer\Groups({"Deposits", "DepositCallback"})
     * @Serializer\Type("string")
     */
    private $amountFilled = '0.00000000';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_fulfilled", type="boolean")
     *
     * @Serializer\Groups({"Deposits", "DepositCallback"})
     * @Serializer\Type("boolean")
     */
    private $isFulfilled = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_overfilled", type="boolean")
     *
     * @Serializer\Groups({"Deposits", "DepositCallback"})
     * @Serializer\Type("boolean")
     */
    private $isOverfilled = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expires_at", type="datetime", nullable=true)
     *
     * @Serializer\Exclude
     */
    private $expiresAt;

    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Application
     *
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="deposits")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id", nullable=false)
     *
     * @Serializer\Exclude
     */
    private $application;

    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Address
     *
     * @ORM\OneToOne(targetEntity="Address", inversedBy="deposit")
     * @ORM\JoinColumn(name="address_external_id", referencedColumnName="id", nullable=false)
     *
     * @Serializer\Groups({"Deposits", "DepositCallback"})
     * @Serializer\Type("Dizda\Bundle\AppBundle\Entity\Address")
     *
     **/
    private $addressExternal;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity    = "DepositTopup",
     *      mappedBy        = "deposit"
     * )
     *
     * @Serializer\Exclude
     */
    private $topups;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->topups = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
     *
     * @return string 
     */
    public function getAmountFilled()
    {
        return $this->amountFilled;
    }

    /**
     * Set isFulfilled
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
     *
     * @return boolean 
     */
    public function getIsFulfilled()
    {
        return $this->isFulfilled;
    }

    /**
     * Set expiresAt
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
     *
     * @return \Dizda\Bundle\AppBundle\Entity\Application 
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Set isOverfilled
     * @codeCoverageIgnore
     *
     * @param boolean $isOverfilled
     * @return Deposit
     */
    public function setIsOverfilled($isOverfilled)
    {
        $this->isOverfilled = $isOverfilled;

        return $this;
    }

    /**
     * Get isOverfilled
     * @codeCoverageIgnore
     *
     * @return boolean 
     */
    public function getIsOverfilled()
    {
        return $this->isOverfilled;
    }

    /**
     * Add topups
     * @codeCoverageIgnore
     *
     * @param \Dizda\Bundle\AppBundle\Entity\DepositTopup $topups
     * @return Deposit
     */
    public function addTopup(\Dizda\Bundle\AppBundle\Entity\DepositTopup $topups)
    {
        $this->topups[] = $topups;

        return $this;
    }

    /**
     * Remove topups
     * @codeCoverageIgnore
     *
     * @param \Dizda\Bundle\AppBundle\Entity\DepositTopup $topups
     */
    public function removeTopup(\Dizda\Bundle\AppBundle\Entity\DepositTopup $topups)
    {
        $this->topups->removeElement($topups);
    }

    /**
     * Get topups
     * @codeCoverageIgnore
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTopups()
    {
        return $this->topups;
    }
}
