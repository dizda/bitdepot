<?php

namespace Dizda\Bundle\BlockchainBundle\Model\Insight;

use JMS\Serializer\Annotation as Serializer;

class TransactionList
{

    /**
     * @var string
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("pagesTotal")
     */
    protected $pagesTotal;

    /**
     * @var integer
     *
     * @Serializer\Type("array<Dizda\Bundle\BlockchainBundle\Model\Insight\Transaction>")
     */
    protected $txs;

    /**
     * @return string
     */
    public function getPagesTotal()
    {
        return $this->pagesTotal;
    }

    /**
     * @return int
     */
    public function getTxs()
    {
        return $this->txs;
    }



}
