<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Keychain
 *
 * @ORM\Table(name="keychain")
 * @ORM\Entity
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
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="sign_required", type="smallint")
     */
    private $signRequired;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity    = "Dizda\Bundle\AppBundle\Entity\Pubkey",
     *      mappedBy        = "keychain"
     * )
     */
    private $pubKeys;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity    = "Dizda\Bundle\AppBundle\Entity\Address",
     *      mappedBy        = "keychain"
     * )
     */
    private $addresses;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pubKeys = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Keychain
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
     * Set signRequired
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
     *
     * @return integer 
     */
    public function getSignRequired()
    {
        return $this->signRequired;
    }

    /**
     * Add pubKeys
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Pubkey $pubKeys
     * @return Keychain
     */
    public function addPubKey(\Dizda\Bundle\AppBundle\Entity\Pubkey $pubKeys)
    {
        $this->pubKeys[] = $pubKeys;

        return $this;
    }

    /**
     * Remove pubKeys
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Pubkey $pubKeys
     */
    public function removePubKey(\Dizda\Bundle\AppBundle\Entity\Pubkey $pubKeys)
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
     * @return Keychain
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
}
