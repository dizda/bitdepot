<?php

namespace Dizda\Bundle\BlockchainBundle\Blockchain;

abstract class BlockchainBase
{
    /**
     * @var \JMS\Serializer\SerializerInterface
     */
    protected $serializer;

    /**
     * @return \JMS\Serializer\SerializerInterface
     */
    protected function getSerializer()
    {
        return $this->serializer;
    }

}