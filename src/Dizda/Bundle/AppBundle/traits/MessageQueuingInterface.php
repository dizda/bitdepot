<?php

namespace Dizda\Bundle\AppBundle\Traits;

/**
 * Interface MessageQueuingInterface
 */
interface MessageQueuingInterface
{

    const QUEUE_STATUS_QUEUED     = 1; // Sent to rabbitmq, but not processed yet
    const QUEUE_STATUS_PROCESSED  = 2; // Processed
    const QUEUE_STATUS_CANCELLED  = 3; // Cancelled for X reasons

    public function setQueueStatus($status);
    public function getQueueStatus();
}
