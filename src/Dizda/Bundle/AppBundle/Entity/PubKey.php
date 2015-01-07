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
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Serializer\Groups({"WithdrawDetail"})
     * @Serializer\Type("string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="extended_pub_key", type="string", length=255)
     *
     * @Serializer\Groups({"WithdrawDetail"})
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
     * @return PubKey
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
     * Set extendedPubKey
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
     *
     * @return \Dizda\Bundle\AppBundle\Entity\Application
     */
    public function getApplication()
    {
        return $this->application;
    }
}
