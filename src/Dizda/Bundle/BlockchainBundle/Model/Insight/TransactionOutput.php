<?php

namespace Dizda\Bundle\BlockchainBundle\Model\Insight;

use Dizda\Bundle\BlockchainBundle\Model\TransactionOutputAbstract;
use JMS\Serializer\Annotation as Serializer;

class TransactionOutput extends TransactionOutputAbstract
{

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("value")
     */
    protected $value;

    /**
     * @var string
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("n")
     */
    protected $index;

    /**
     * @var string
     *
     * @Serializer\Type("array")
     * @Serializer\SerializedName("scriptPubKey")
     * @Serializer\Accessor(setter="setAddresses")
     */
    protected $addresses;

    public function setAddresses($addresses)
    {
        $this->addresses = $addresses['addresses'];
    }
}
