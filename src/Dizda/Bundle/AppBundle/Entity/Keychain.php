<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Keychain
 *
 * @ORM\Table(name="keychain")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Keychain
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
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Serializer\Groups({"Withdraws", "WithdrawDetail"})
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="sign_required", type="smallint")
     *
     * @Serializer\Groups({"Withdraws", "WithdrawDetail"})
     */
    private $signRequired;

    /**
     * @var integer
     *
     * @ORM\Column(name="group_withdraws_by_quantity", type="smallint", nullable=true)
     */
    private $groupWithdrawsByQuantity;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity    = "Application",
     *      mappedBy        = "keychain"
     * )
     *
     * @Serializer\Exclude
     */
    private $applications;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity    = "Withdraw",
     *      mappedBy        = "keychain"
     * )
     *
     * @Serializer\Exclude
     */
    private $withdraws;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity    = "Identity",
     *      mappedBy        = "keychain"
     * )
     *
     * @Serializer\Groups({"WithdrawDetail"})
     */
    private $identities;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pubKeys = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     * @codeCoverageIgnore
     *
     * @param string $name
     * @return Keychain
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set signRequired
     * @codeCoverageIgnore
     *
     * @param integer $signRequired
     * @return Keychain
     */
    public function setSignRequired($signRequired)
    {
        $this->signRequired = $signRequired;

        return $this;
    }

    /**
     * Get signRequired
     * @codeCoverageIgnore
     *
     * @return integer
     */
    public function getSignRequired()
    {
        return $this->signRequired;
    }

    /**
     * Add applications
     * @codeCoverageIgnore
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Application $applications
     * @return Keychain
     */
    public function addApplication(\Dizda\Bundle\AppBundle\Entity\Application $applications)
    {
        $this->applications[] = $applications;

        return $this;
    }

    /**
     * Remove applications
     * @codeCoverageIgnore
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Application $applications
     */
    public function removeApplication(\Dizda\Bundle\AppBundle\Entity\Application $applications)
    {
        $this->applications->removeElement($applications);
    }

    /**
     * Get applications
     * @codeCoverageIgnore
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * Add withdraws
     * @codeCoverageIgnore
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Withdraw $withdraws
     * @return Keychain
     */
    public function addWithdraw(\Dizda\Bundle\AppBundle\Entity\Withdraw $withdraws)
    {
        $this->withdraws[] = $withdraws;

        return $this;
    }

    /**
     * Remove withdraws
     * @codeCoverageIgnore
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Withdraw $withdraws
     */
    public function removeWithdraw(\Dizda\Bundle\AppBundle\Entity\Withdraw $withdraws)
    {
        $this->withdraws->removeElement($withdraws);
    }

    /**
     * Get withdraws
     * @codeCoverageIgnore
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWithdraws()
    {
        return $this->withdraws;
    }

    /**
     * Add identities
     * @codeCoverageIgnore
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Identity $identities
     * @return Keychain
     */
    public function addIdentity(\Dizda\Bundle\AppBundle\Entity\Identity $identities)
    {
        $this->identities[] = $identities;

        return $this;
    }

    /**
     * Remove identities
     * @codeCoverageIgnore
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Identity $identities
     */
    public function removeIdentity(\Dizda\Bundle\AppBundle\Entity\Identity $identities)
    {
        $this->identities->removeElement($identities);
    }

    /**
     * Get identities
     * @codeCoverageIgnore
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIdentities()
    {
        return $this->identities;
    }

    /**
     * Set groupWithdrawsByQuantity
     * @codeCoverageIgnore
     *
     * @param integer $groupWithdrawsByQuantity
     * @return Keychain
     */
    public function setGroupWithdrawsByQuantity($groupWithdrawsByQuantity)
    {
        $this->groupWithdrawsByQuantity = $groupWithdrawsByQuantity;

        return $this;
    }

    /**
     * Get groupWithdrawsByQuantity
     * @codeCoverageIgnore
     *
     * @return integer
     */
    public function getGroupWithdrawsByQuantity()
    {
        return $this->groupWithdrawsByQuantity;
    }
}
