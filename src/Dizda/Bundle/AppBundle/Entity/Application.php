<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Dizda\Bundle\AppBundle\Traits\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Application
 *
 * @ORM\Table(name="application")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
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
     *
     * @Serializer\Groups({"WithdrawDetail"})
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
     *      targetEntity    = "PubKey",
     *      mappedBy        = "application"
     * )
     */
    private $pubKeys;

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
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity    = "Dizda\Bundle\AppBundle\Entity\Address",
     *      mappedBy        = "application"
     * )
     *
     * @Serializer\Exclude
     */
    private $addresses;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->deposits  = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pubKeys   = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set appId
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * Set appSecret
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function getAppSecret()
    {
        return $this->appSecret;
    }

    /**
     * Add deposits
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Deposit $deposits
     */
    public function removeDeposit(\Dizda\Bundle\AppBundle\Entity\Deposit $deposits)
    {
        $this->deposits->removeElement($deposits);
    }

    /**
     * Get deposits
     * @codeCoverageIgnore
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDeposits()
    {
        return $this->deposits;
    }

    /**
     * Set keychain
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function getCallbackEndpoint()
    {
        return $this->callbackEndpoint;
    }

    /**
     * Set groupWithdrawsByQuantity
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
     *
     * @return integer
     */
    public function getGroupWithdrawsByQuantity()
    {
        return $this->groupWithdrawsByQuantity;
    }

    /**
     * Add pubKeys
     *
     * @param \Dizda\Bundle\AppBundle\Entity\PubKey $pubKeys
     * @return Application
     */
    public function addPubKey(\Dizda\Bundle\AppBundle\Entity\PubKey $pubKeys)
    {
        $this->pubKeys[] = $pubKeys;

        return $this;
    }

    /**
     * Remove pubKeys
     *
     * @param \Dizda\Bundle\AppBundle\Entity\PubKey $pubKeys
     */
    public function removePubKey(\Dizda\Bundle\AppBundle\Entity\PubKey $pubKeys)
    {
        $this->pubKeys->removeElement($pubKeys);
    }

    /**
     * Get pubKeys
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPubKeys()
    {
        return $this->pubKeys;
    }

    /**
     * Add addresses
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Address $addresses
     * @return Application
     */
    public function addAddress(\Dizda\Bundle\AppBundle\Entity\Address $addresses)
    {
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
     * Get pubKeys that can be serialized
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPubKeysSerializable()
    {
        return $this->getPubKeys()->map(function ($item) {
            return $item->getExtendedPubKey();
        });
    }
}
