<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * PubKey
 *
 * @ORM\Table(name="pub_key")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class PubKey
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
     * @ORM\Column(name="extended_pub_key", type="string", length=255)
     *
     * @Serializer\Type("string")
     */
    private $extendedPubKey;

    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Application
     *
     * @ORM\ManyToOne(targetEntity="Dizda\Bundle\AppBundle\Entity\Application", inversedBy="pubKeys")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id", nullable=false)
     */
    private $application;

    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Identity
     *
     * @ORM\ManyToOne(targetEntity="Identity", inversedBy="pubKeys")
     * @ORM\JoinColumn(name="identity_id", referencedColumnName="id", nullable=false)
     */
    private $identity;

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
     * Set extendedPubKey
     * @codeCoverageIgnore
     *
     * @param string $extendedPubKey
     * @return PubKey
     */
    public function setExtendedPubKey($extendedPubKey)
    {
        $this->extendedPubKey = $extendedPubKey;

        return $this;
    }

    /**
     * Get extendedPubKey
     *
     * @return string
     */
    public function getExtendedPubKey()
    {
        return $this->extendedPubKey;
    }

    /**
     * Set application
     * @codeCoverageIgnore
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Application $application
     * @return PubKey
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
     * Set identity
     * @codeCoverageIgnore
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Identity $identity
     * @return PubKey
     */
    public function setIdentity(\Dizda\Bundle\AppBundle\Entity\Identity $identity)
    {
        $this->identity = $identity;

        return $this;
    }

    /**
     * Get identity
     * @codeCoverageIgnore
     *
     * @return \Dizda\Bundle\AppBundle\Entity\Identity
     */
    public function getIdentity()
    {
        return $this->identity;
    }
}
