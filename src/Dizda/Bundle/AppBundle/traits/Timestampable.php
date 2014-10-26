<?php

namespace Dizda\Bundle\AppBundle\Traits;

/**
 * Trait Timestampable
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
trait Timestampable
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * Add creation date when the object is created
     *
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Set createdAt
     * @codeCoverageIgnore
     *
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     * @codeCoverageIgnore
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
