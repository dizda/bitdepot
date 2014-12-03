<?php

namespace Dizda\Bundle\BlockchainBundle\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class TransactionOutputAbstract
 */
abstract class TransactionOutputAbstract
{

    /**
     * @var string
     */
    protected $value;

    /**
     * Return the output index
     *
     * @var string
     */
    protected $index;

    /**
     * @var array
     */
    protected $addresses;

    /**
     * @return string
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param string $index
     *
     * @return $this
     */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }


}
