<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Dizda\Bundle\AppBundle\Traits\MessageQueuing;
use Dizda\Bundle\AppBundle\Traits\MessageQueuingInterface;
use Dizda\Bundle\AppBundle\Traits\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * WithdrawOutput
 *
 * @ORM\Table(name="withdraw_output")
 * @ORM\Entity(repositoryClass="Dizda\Bundle\AppBundle\Repository\WithdrawOutputRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class WithdrawOutput implements MessageQueuingInterface
{
    use Timestampable, MessageQueuing;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Groups({"WithdrawOutputs", "WithdrawOutputCallback"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=16, scale=8, nullable=false, options={"default"=0})
     *
     * @Serializer\Groups({"WithdrawDetail", "WithdrawOutputs", "WithdrawOutputCallback"})
     * @Serializer\Type("string")
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="to_address", type="string", length=255)
     *
     * @Serializer\Groups({"WithdrawDetail", "WithdrawOutputs", "WithdrawOutputCallback"})
     */
    private $toAddress;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_accepted", type="boolean")
     *
     * @Serializer\Groups({"WithdrawOutputs"})
     */
    private $isAccepted;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255, nullable=true)
     *
     * @Serializer\Groups({"WithdrawDetail", "WithdrawOutputs", "WithdrawOutputCallback"})
     */
    private $reference;

    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Application
     *
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="withdrawOutputs")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id", nullable=false)
     *
     * @Serializer\Exclude
     */
    private $application;

    /**
     * Withdraw can be NULL until a grouped withdraw has been created.
     *
     * @var \Dizda\Bundle\AppBundle\Entity\Withdraw
     *
     * @ORM\ManyToOne(targetEntity="Withdraw", inversedBy="withdrawOutputs")
     * @ORM\JoinColumn(name="withdraw_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     *
     * Serializer\Exclude
     * @Serializer\Groups({"WithdrawOutputs", "WithdrawOutputCallback"})
     * @Serializer\Type("Dizda\Bundle\AppBundle\Entity\Withdraw")
     */
    private $withdraw;

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
     * @return WithdrawOutput
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
     * Set toAddress
     *
     * @param string $toAddress
     * @return WithdrawOutput
     */
    public function setToAddress($toAddress)
    {
        $this->toAddress = $toAddress;

        return $this;
    }

    /**
     * Get toAddress
     *
     * @return string
     */
    public function getToAddress()
    {
        return $this->toAddress;
    }

    /**
     * Set isAccepted
     *
     * @param boolean $isAccepted
     * @return WithdrawOutput
     */
    public function setIsAccepted($isAccepted)
    {
        $this->isAccepted = $isAccepted;

        return $this;
    }

    /**
     * Get isAccepted
     *
     * @return boolean
     */
    public function getIsAccepted()
    {
        return $this->isAccepted;
    }

    /**
     * Set reference
     *
     * @param string $reference
     * @return WithdrawOutput
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set withdraw
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Withdraw $withdraw
     * @return WithdrawOutput
     */
    public function setWithdraw(\Dizda\Bundle\AppBundle\Entity\Withdraw $withdraw = null)
    {
        $this->withdraw = $withdraw;

        return $this;
    }

    /**
     * Get withdraw
     *
     * @return \Dizda\Bundle\AppBundle\Entity\Withdraw
     */
    public function getWithdraw()
    {
        return $this->withdraw;
    }

    /**
     * Set application
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Application $application
     * @return WithdrawOutput
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
}

