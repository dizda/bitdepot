<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Dizda\Bundle\AppBundle\Traits\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Application
 *
 * @ORM\Table(name="application")
 * @ORM\Entity(repositoryClass="Dizda\Bundle\AppBundle\Repository\ApplicationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Application implements UserInterface
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
     *
     * @Serializer\Groups({"Applications"})
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
     *
     * @Serializer\Groups({"Applications"})
     */
    private $confirmationsRequired;

    /**
     * Callback API Endpoint
     *
     * @var string
     *
     * @ORM\Column(name="callback_endpoint", type="string", length=255)
     *
     * @Serializer\Groups({"Applications"})
     */
    private $callbackEndpoint;

    /**
     * Strings accepted by \DateTime
     *  eg. "+12 hours"
     *  eg. "+2 days"
     *
     * @var string
     *
     * @ORM\Column(name="deposits_expires_after", type="string", length=20, options={"default" = "+360 days"})
     *
     * @Serializer\Groups({"Applications"})
     */
    private $depositsExpiresAfter;

    /**
     * Strings accepted by \DateTime
     *  eg. "+12 hours"
     *  eg. "+2 days"
     *
     * @var string
     *
     * @ORM\Column(name="deposits_topups_expires_after", type="string", length=20, options={"default" = "+360 days"})
     *
     * @Serializer\Groups({"Applications"})
     */
    private $depositsTopupsExpiresAfter;

    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Application
     *
     * @ORM\ManyToOne(targetEntity="Keychain", inversedBy="applications")
     * @ORM\JoinColumn(name="keychain_id", referencedColumnName="id", nullable=true)
     *
     * @Serializer\Groups({"Applications", "TransactionBuilder"})
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
     * @codeCoverageIgnore
     *
     * @param $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Add pubKeys
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Address $addresses
     */
    public function removeAddress(\Dizda\Bundle\AppBundle\Entity\Address $addresses)
    {
        $this->addresses->removeElement($addresses);
    }

    /**
     * Get addresses
     * @codeCoverageIgnore
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

    /**
     * When loggin via WSSE-Auth, the ROLE_WSSE is attributed to the user/application
     *
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getRoles()
    {
        return ['ROLE_WSSE'];
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getPassword()
    {
        return $this->appSecret;
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getSalt()
    {
//        return $this->salt;
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getUsername()
    {
        return $this->appId;
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function eraseCredentials()
    {
    }

    /**
     * Set depositsExpiresAfter
     *
     * @param string $depositsExpiresAfter
     * @return Application
     */
    public function setDepositsExpiresAfter($depositsExpiresAfter)
    {
        $this->depositsExpiresAfter = $depositsExpiresAfter;

        return $this;
    }

    /**
     * Get depositsExpiresAfter
     *
     * @return string
     */
    public function getDepositsExpiresAfter()
    {
        return $this->depositsExpiresAfter;
    }

    /**
     * Set depositsTopupsExpiresAfter
     *
     * @param string $depositsTopupsExpiresAfter
     * @return Application
     */
    public function setDepositsTopupsExpiresAfter($depositsTopupsExpiresAfter)
    {
        $this->depositsTopupsExpiresAfter = $depositsTopupsExpiresAfter;

        return $this;
    }

    /**
     * Get depositsTopupsExpiresAfter
     *
     * @return string
     */
    public function getDepositsTopupsExpiresAfter()
    {
        return $this->depositsTopupsExpiresAfter;
    }
}
