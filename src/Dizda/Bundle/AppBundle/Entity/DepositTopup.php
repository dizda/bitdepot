<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Dizda\Bundle\AppBundle\Traits\MessageQueuing;
use Dizda\Bundle\AppBundle\Traits\MessageQueuingInterface;
use Dizda\Bundle\AppBundle\Traits\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * DepositTopup
 *
 * @ORM\Table(name="deposit_topup")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class DepositTopup implements MessageQueuingInterface
{
    use Timestampable;
    use MessageQueuing;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Type("integer")
     */
    private $id;

    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Transaction
     *
     * @ORM\OneToOne(targetEntity="Transaction", inversedBy="topup")
     * @ORM\JoinColumn(name="address_transaction_id", referencedColumnName="id", nullable=false)
     *
     * @Serializer\Type("Dizda\Bundle\AppBundle\Entity\Transaction")
     **/
    private $transaction;

    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Deposit
     *
     * @ORM\ManyToOne(targetEntity="Deposit", inversedBy="topups")
     * @ORM\JoinColumn(name="deposit_id", referencedColumnName="id", nullable=false)
     *
     * @Serializer\Type("Dizda\Bundle\AppBundle\Entity\Deposit")
     */
    private $deposit;

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
     * Set deposit
     * @codeCoverageIgnore
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Deposit $deposit
     * @return DepositTopup
     */
    public function setDeposit(\Dizda\Bundle\AppBundle\Entity\Deposit $deposit)
    {
        $this->deposit = $deposit;

        return $this;
    }

    /**
     * Get deposit
     * @codeCoverageIgnore
     *
     * @return \Dizda\Bundle\AppBundle\Entity\Deposit
     */
    public function getDeposit()
    {
        return $this->deposit;
    }

    /**
     * Set transaction
     * @codeCoverageIgnore
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Transaction $transaction
     * @return DepositTopup
     */
    public function setTransaction(\Dizda\Bundle\AppBundle\Entity\Transaction $transaction)
    {
        $this->transaction = $transaction;

        return $this;
    }

    /**
     * Get transaction
     * @codeCoverageIgnore
     *
     * @return \Dizda\Bundle\AppBundle\Entity\Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}
