<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Keychain
 *
 * @ORM\Table(name="identity")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Identity
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
     * @Serializer\Groups({"Applications", "WithdrawDetail"})
     */
    private $name;

    /**
     * The public key match to m/44'/0'/0'/0/0
     *
     * @var string
     *
     * @ORM\Column(name="public_key", type="string", length=255)
     *
     * @Serializer\Groups({"WithdrawDetail"})
     */
    private $publicKey;

    /**
     * @var Keychain
     *
     * @ORM\ManyToOne(targetEntity="Keychain", inversedBy="identities")
     * @ORM\JoinColumn(name="keychain_id", referencedColumnName="id", nullable=false)
     *
     * @Serializer\Exclude
     */
    private $keychain;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity    = "PubKey",
     *      mappedBy        = "identity"
     * )
     *
     * @Serializer\Exclude
     */
    private $pubKeys;

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
     * @return Identity
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
     * Set publicKey
     * @codeCoverageIgnore
     *
     * @param string $publicKey
     * @return Identity
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    /**
     * Get publicKey
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }


    /**
     * Set keychain
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Keychain $keychain
     * @return Identity
     */
    public function setKeychain(\Dizda\Bundle\AppBundle\Entity\Keychain $keychain)
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
     * Add pubKeys
     * @codeCoverageIgnore
     *
     * @param \Dizda\Bundle\AppBundle\Entity\PubKey $pubKeys
     * @return Identity
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
     * @codeCoverageIgnore
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPubKeys()
    {
        return $this->pubKeys;
    }
}
