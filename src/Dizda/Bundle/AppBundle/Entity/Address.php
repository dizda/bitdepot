<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Address
 *
 * @ORM\Table(name="address")
 * @ORM\Entity
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
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_external", type="boolean")
     */
    private $isExternal;

    /**
     * @var integer
     *
     * @ORM\Column(name="derivation", type="integer")
     */
    private $derivation;

    /**
     * @var string
     *
     * @ORM\Column(name="balance", type="decimal")
     */
    private $balance;

    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Keychain
     *
     * @ORM\ManyToOne(targetEntity="Dizda\Bundle\AppBundle\Entity\Keychain", inversedBy="addresses")
     * @ORM\JoinColumn(name="keychain_id", referencedColumnName="id", nullable=false)
     */
    private $keychain;

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
}
