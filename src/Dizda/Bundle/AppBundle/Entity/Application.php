<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Dizda\Bundle\AppBundle\Traits\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application
 *
 * @ORM\Table(name="application")
 * @ORM\Entity
 */
class Application
{
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="app_id", type="string", length=255)
     */
    private $appId;

    /**
     * @var string
     *
     * @ORM\Column(name="app_secret", type="string", length=255)
     */
    private $appSecret;

    /**
     * @var integer
     *
     * @ORM\Column(name="confirmations_required", type="smallint")
     */
    private $confirmationsRequired;

    /**
     * Callback API Endpoint
     *
     * @var string
     *
     * @ORM\Column(name="callback_endpoint", type="string", length=255)
     */
    private $callbackEndpoint;

    /**
     * @var integer
     *
     * @ORM\Column(name="group_withdraws_by_quantity", type="smallint", nullable=true)
     */
    private $groupWithdrawsByQuantity;

    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Application
     *
     * @ORM\ManyToOne(targetEntity="Keychain", inversedBy="applications")
     * @ORM\JoinColumn(name="keychain_id", referencedColumnName="id", nullable=true)
     */
    private $keychain;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity    = "Deposit",
     *      mappedBy        = "application"
     * )
     */
    private $deposits;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->deposits = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Application
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set appId
     *
     * @param string $appId
     * @return Application
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;

        return $this;
    }

    /**
     * Get appId
     *
     * @return string 
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * Set appSecret
     *
     * @param string $appSecret
     * @return Application
     */
    public function setAppSecret($appSecret)
    {
        $this->appSecret = $appSecret;

        return $this;
    }

    /**
     * Get appSecret
     *
     * @return string 
     */
    public function getAppSecret()
    {
        return $this->appSecret;
    }

    /**
     * Add deposits
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Deposit $deposits
     * @return Application
     */
    public function addDeposit(\Dizda\Bundle\AppBundle\Entity\Deposit $deposits)
    {
        $this->deposits[] = $deposits;

        return $this;
    }

    /**
     * Remove deposits
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Deposit $deposits
     */
    public function removeDeposit(\Dizda\Bundle\AppBundle\Entity\Deposit $deposits)
    {
        $this->deposits->removeElement($deposits);
    }

    /**
     * Get deposits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDeposits()
    {
        return $this->deposits;
    }

    /**
     * Set keychain
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Keychain $keychain
     * @return Application
     */
    public function setKeychain(\Dizda\Bundle\AppBundle\Entity\Keychain $keychain = null)
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
     * Set confirmationsRequired
     *
     * @param integer $confirmationsRequired
     * @return Application
     */
    public function setConfirmationsRequired($confirmationsRequired)
    {
        $this->confirmationsRequired = $confirmationsRequired;

        return $this;
    }

    /**
     * Get confirmationsRequired
     *
     * @return integer 
     */
    public function getConfirmationsRequired()
    {
        return $this->confirmationsRequired;
    }

    /**
     * Set callbackEndpoint
     *
     * @param string $callbackEndpoint
     * @return Application
     */
    public function setCallbackEndpoint($callbackEndpoint)
    {
        $this->callbackEndpoint = $callbackEndpoint;

        return $this;
    }

    /**
     * Get callbackEndpoint
     *
     * @return string 
     */
    public function getCallbackEndpoint()
    {
        return $this->callbackEndpoint;
    }

    /**
     * Set groupWithdrawsByQuantity
     *
     * @param integer $groupWithdrawsByQuantity
     * @return Application
     */
    public function setGroupWithdrawsByQuantity($groupWithdrawsByQuantity)
    {
        $this->groupWithdrawsByQuantity = $groupWithdrawsByQuantity;

        return $this;
    }

    /**
     * Get groupWithdrawsByQuantity
     *
     * @return integer 
     */
    public function getGroupWithdrawsByQuantity()
    {
        return $this->groupWithdrawsByQuantity;
    }
}
