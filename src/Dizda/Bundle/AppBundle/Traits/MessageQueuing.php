<?php

namespace Dizda\Bundle\AppBundle\Traits;

/**
 * Trait MessageQueuing
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
trait MessageQueuing
{
    /**
     * @var integer
     *
     * @ORM\Column(name="queue_status", type="smallint")
     *
     * @Serializer\Exclude()
     */
    private $queueStatus = 0;

    /**
     * Set queueStatus
     *
     * @param integer $queueStatus
     *
     * @return $this
     */
    public function setQueueStatus($queueStatus)
    {
        $this->queueStatus = $queueStatus;

        return $this;
    }

    /**
     * Get queueStatus
     *
     * @return integer
     */
    public function getQueueStatus()
    {
        return $this->queueStatus;
    }
}
