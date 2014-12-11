<?php

namespace Dizda\Bundle\BlockchainBundle\Model\Insight;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @Serializer\Type("ArrayCollection<Dizda\Bundle\BlockchainBundle\Model\Insight\Transaction>")
     */
    protected $txs;

    /**
     * @codeCoverageIgnore
     *
     * @return integer
     */
    public function getPagesTotal()
    {
        return $this->pagesTotal;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return ArrayCollection
     */
    public function getTxs()
    {
        return $this->txs;
    }



}
