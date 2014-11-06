<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Withdraw
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Withdraw
{
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
     * @ORM\Column(name="total_amount", type="decimal")
     */
    private $totalAmount;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_signed", type="boolean")
     */
    private $isSigned;

    /**
     * @var string
     *
     * @ORM\Column(name="raw_transaction", type="text")
     */
    private $rawTransaction;

    /**
     * @var string
     *
     * @ORM\Column(name="raw_signed_transaction", type="text")
     */
    private $rawSignedTransaction;

    /**
     * @var string
     *
     * @ORM\Column(name="amount_transferred_to_change", type="decimal")
     */
    private $amountTransferredToChange;

    /**
     * @var string
     *
     * @ORM\Column(name="fees", type="decimal")
     */
    private $fees;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set totalAmount
     *
     * @param string $totalAmount
     * @return Withdraw
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    /**
     * Get totalAmount
     *
     * @return string
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * Set isSigned
     *
     * @param boolean $isSigned
     * @return Withdraw
     */
    public function setIsSigned($isSigned)
    {
        $this->isSigned = $isSigned;

        return $this;
    }

    /**
     * Get isSigned
     *
     * @return boolean
     */
    public function getIsSigned()
    {
        return $this->isSigned;
    }

    /**
     * Set rawTransaction
     *
     * @param string $rawTransaction
     * @return Withdraw
     */
    public function setRawTransaction($rawTransaction)
    {
        $this->rawTransaction = $rawTransaction;

        return $this;
    }

    /**
     * Get rawTransaction
     *
     * @return string
     */
    public function getRawTransaction()
    {
        return $this->rawTransaction;
    }

    /**
     * Set rawSignedTransaction
     *
     * @param string $rawSignedTransaction
     * @return Withdraw
     */
    public function setRawSignedTransaction($rawSignedTransaction)
    {
        $this->rawSignedTransaction = $rawSignedTransaction;

        return $this;
    }

    /**
     * Get rawSignedTransaction
     *
     * @return string
     */
    public function getRawSignedTransaction()
    {
        return $this->rawSignedTransaction;
    }

    /**
     * Set amountTransferredToChange
     *
     * @param string $amountTransferredToChange
     * @return Withdraw
     */
    public function setAmountTransferredToChange($amountTransferredToChange)
    {
        $this->amountTransferredToChange = $amountTransferredToChange;

        return $this;
    }

    /**
     * Get amountTransferredToChange
     *
     * @return string
     */
    public function getAmountTransferredToChange()
    {
        return $this->amountTransferredToChange;
    }

    /**
     * Set fees
     *
     * @param string $fees
     * @return Withdraw
     */
    public function setFees($fees)
    {
        $this->fees = $fees;

        return $this;
    }

    /**
     * Get fees
     *
     * @return string
     */
    public function getFees()
    {
        return $this->fees;
    }
}
