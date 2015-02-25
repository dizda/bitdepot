<?php

namespace Dizda\Bundle\AppBundle\Entity;

use Dizda\Bundle\AppBundle\Traits\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Withdraw
 *
 * @ORM\Table(name="withdraw")
 * @ORM\Entity(repositoryClass="Dizda\Bundle\AppBundle\Repository\WithdrawRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Withdraw
{
    use Timestampable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Groups({"Withdraws", "WithdrawDetail", "WithdrawOutputs"})
     */
    private $id;

    /**
     * Total took from inputs, and the difference with $totalOutputs
     * need to be sent to $amountTransferredToChange (change address)
     *
     * @var string
     *
     * @ORM\Column(name="total_inputs", type="decimal", precision=16, scale=8, nullable=false, options={"default"=0})
     *
     * @Serializer\Groups({"Withdraws", "WithdrawDetail"})
     * @Serializer\Type("string")
     */
    private $totalInputs;

    /**
     * Total to withdraw
     *
     * @var string
     *
     * @ORM\Column(name="total_outputs", type="decimal", precision=16, scale=8, nullable=false, options={"default"=0})
     *
     * @Serializer\Groups({"Withdraws", "WithdrawDetail"})
     * @Serializer\Type("string")
     */
    private $totalOutputs;

    /**
     * This field is NULL until a withdraw is processed.
     * After that, if we don't need to seed any amount to a change address, this field will be set to 0.
     *
     * @var string
     *
     * @ORM\Column(name="change_address_amount", type="decimal", precision=16, scale=8, nullable=true)
     *
     * @Serializer\Groups({"WithdrawList"})
     */
    private $changeAddressAmount;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_signed", type="boolean")
     *
     * @Serializer\Groups({"Withdraws", "WithdrawDetail"})
     * @Serializer\Type("boolean")
     */
    private $isSigned = false;

    /**
     * @var string
     *
     * @ORM\Column(name="raw_transaction", type="text", length=65535, nullable=true)
     *
     * @Serializer\Groups({"WithdrawDetail"})
     */
    private $rawTransaction;

    /**
     * @var string
     *
     * @ORM\Column(name="raw_signed_transaction", type="text", length=65535, nullable=true)
     *
     * @Serializer\Groups({"WithdrawDetail"})
     */
    private $rawSignedTransaction;

    /**
     * Same than above.
     *
     * @var string
     *
     * @ORM\Column(name="fees", type="decimal", precision=16, scale=8, nullable=true)
     *
     * @Serializer\Groups({"Withdraws", "WithdrawDetail"})
     * @Serializer\Type("string")
     */
    private $fees;

    /**
     * @var string
     *
     * @ORM\Column(name="txid", type="string", length=255, nullable=true)
     *
     * @Serializer\Groups({"Withdraws", "WithdrawDetail", "WithdrawOutputCallback"})
     */
    private $txid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="withdrawed_at", type="datetime", nullable=true)
     *
     * @Serializer\Groups({"Withdraws", "WithdrawDetail", "WithdrawOutputs", "WithdrawOutputCallback"})
     */
    private $withdrawedAt;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity    = "Transaction",
     *      mappedBy        = "withdraw"
     * )
     *
     * @Serializer\Groups({"Withdraws", "WithdrawDetail"})
     * @Serializer\Type("array<Dizda\Bundle\AppBundle\Entity\Transaction>")
     */
    private $withdrawInputs;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity    = "WithdrawOutput",
     *      mappedBy        = "withdraw"
     * )
     *
     * Serializer\Groups({"Withdraw"})
     * Serializer\Accessor(getter="getWithdrawOutputsSerialized")
     * Serializer\Type("array")
     *
     * @Serializer\Groups({"Withdraws", "WithdrawDetail"})
     */
    private $withdrawOutputs;

    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Address
     *
     * @ORM\OneToOne(targetEntity="Address", inversedBy="withdrawChangeAddress")
     * @ORM\JoinColumn(name="change_address", referencedColumnName="id", nullable=true)
     *
     * @Serializer\Groups({"WithdrawDetail"})
     * @Serializer\Type("Dizda\Bundle\AppBundle\Entity\Address")
     **/
    private $changeAddress;

    /**
     * @var \Dizda\Bundle\AppBundle\Entity\Keychain
     *
     * @ORM\ManyToOne(targetEntity="Keychain", inversedBy="withdraws")
     * @ORM\JoinColumn(name="keychain_id", referencedColumnName="id", nullable=false)
     *
     * @Serializer\Groups({"Withdraws", "WithdrawDetail"})
     * @Serializer\Type("Dizda\Bundle\AppBundle\Entity\Keychain")
     */
    private $keychain;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Identity")
     * @ORM\JoinTable(name="withdraw_signature",
     *     joinColumns={@ORM\JoinColumn(name="withdraw_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="identity_id", referencedColumnName="id")}
     * )
     *
     * @Serializer\Groups({"Withdraws", "WithdrawDetail"})
     * @Serializer\Type("array<Dizda\Bundle\AppBundle\Entity\Identity>")
     */
    private $signatures;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->withdrawInputs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->withdrawOutputs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set the withdraw as withdrawed status
     *
     * @param $txId
     */
    public function withdrawed($txId)
    {
        $this->withdrawedAt = new \DateTime();
        $this->setTxid($txId);
    }

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
     * Set isSigned
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
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

    /**
     * Set withdrawedAt
     * @codeCoverageIgnore
     *
     * @param \DateTime $withdrawedAt
     * @return Withdraw
     */
    public function setWithdrawedAt($withdrawedAt)
    {
        $this->withdrawedAt = $withdrawedAt;

        return $this;
    }

    /**
     * Get withdrawedAt
     *
     * @return \DateTime
     */
    public function getWithdrawedAt()
    {
        return $this->withdrawedAt;
    }

    /**
     * Add withdrawInputs
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Transaction $withdrawInputs
     * @return Withdraw
     */
    public function addWithdrawInput(\Dizda\Bundle\AppBundle\Entity\Transaction $withdrawInputs)
    {
        $this->withdrawInputs[] = $withdrawInputs;

        $withdrawInputs->setWithdraw($this);

        return $this;
    }

    /**
     * Remove withdrawInputs
     * @codeCoverageIgnore
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Transaction $withdrawInputs
     */
    public function removeWithdrawInput(\Dizda\Bundle\AppBundle\Entity\Transaction $withdrawInputs)
    {
        $this->withdrawInputs->removeElement($withdrawInputs);
    }

    /**
     * Get withdrawInputs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWithdrawInputs()
    {
        return $this->withdrawInputs;
    }

    /**
     * Get withdrawInputs
     *
     * @return array
     */
    public function getWithdrawInputsSerializable()
    {
        $inputs = [];

        foreach ($this->withdrawInputs as $input) {
            $inputs[] = [
                'txid' => $input->getTxid(),
                'vout' => $input->getIndex()
            ];
        }

        return $inputs;
    }

    /**
     * @param array $withdrawInputs
     * @codeCoverageIgnore
     *
     * @return $this
     */
    public function setWithdrawInputs(array $withdrawInputs)
    {
        $this->withdrawInputs = $withdrawInputs;

        return $this;
    }

    /**
     * Add withdrawOutputs
     *
     * @param \Dizda\Bundle\AppBundle\Entity\WithdrawOutput $withdrawOutputs
     * @return Withdraw
     */
    public function addWithdrawOutput(\Dizda\Bundle\AppBundle\Entity\WithdrawOutput $withdrawOutputs)
    {
        $this->withdrawOutputs[] = $withdrawOutputs;

        $withdrawOutputs->setWithdraw($this);

        return $this;
    }

    /**
     * Remove withdrawOutputs
     * @codeCoverageIgnore
     *
     * @param \Dizda\Bundle\AppBundle\Entity\WithdrawOutput $withdrawOutputs
     */
    public function removeWithdrawOutput(\Dizda\Bundle\AppBundle\Entity\WithdrawOutput $withdrawOutputs)
    {
        $this->withdrawOutputs->removeElement($withdrawOutputs);
    }

    /**
     * @param array $withdrawOutputs
     * @codeCoverageIgnore
     *
     * @return $this
     */
    public function setWithdrawOutputs(array $withdrawOutputs)
    {
        $this->withdrawOutputs = $withdrawOutputs;

        return $this;
    }

    /**
     * Get withdrawOutputs
     * @codeCoverageIgnore
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWithdrawOutputs()
    {
        return $this->withdrawOutputs;
    }

    /**
     * Get withdrawOutputs, that bitcoindbundle can serialize
     *
     * @return array
     */
    public function getWithdrawOutputsSerializable()
    {
        $outputs = [];

        foreach ($this->withdrawOutputs as $output) {
            // verify is address is already present in outputs to avoid overwrite, but add amount to the address instead
            if (isset($outputs[$output->getToAddress()])) {
                $outputs[$output->getToAddress()] = (float) bcadd($outputs[$output->getToAddress()], $output->getAmount(), 8);
            } else {
                $outputs[$output->getToAddress()] = (float) $output->getAmount();
            }
        }

        // Send change to a change address if amount of inputs are higher than amount needed for the withdraw
        if ($this->getChangeAddress()) {
            $outputs[$this->getChangeAddress()->getValue()] = (float) $this->changeAddressAmount;
        }

        return $outputs;
    }

    /**
     * Set txid
     *
     * @param string $txid
     * @return Withdraw
     */
    public function setTxid($txid)
    {
        $this->txid = $txid;

        return $this;
    }

    /**
     * Get txid
     *
     * @return string
     */
    public function getTxid()
    {
        return $this->txid;
    }

    /**
     * Set totalInputs
     *
     * @param string $totalInputs
     * @return Withdraw
     */
    public function setTotalInputs($totalInputs)
    {
        $this->totalInputs = $totalInputs;

        return $this;
    }

    /**
     * Add totalInputs
     *
     * @param string $input
     * @return Withdraw
     */
    public function addTotalInputs($input)
    {
        $this->totalInputs = bcadd($this->totalInputs, $input, 8);

        return $this;
    }

    /**
     * Get totalInputs
     *
     * @return string
     */
    public function getTotalInputs()
    {
        return $this->totalInputs;
    }

    /**
     * Set totalOutputs
     *
     * @param string $totalOutputs
     * @return Withdraw
     */
    public function setTotalOutputs($totalOutputs)
    {
        $this->totalOutputs = $totalOutputs;

        return $this;
    }

    /**
     * Add totalOutputs
     *
     * @param string $output
     * @return Withdraw
     */
    public function addTotalOutputs($output)
    {
        $this->totalOutputs = bcadd($this->totalOutputs, $output, 8);

        return $this;
    }

    /**
     * Get totalOutputs
     *
     * @return string
     */
    public function getTotalOutputs()
    {
        return $this->totalOutputs;
    }

    /**
     * Get totalOutputs with fees
     *
     * @return string
     */
    public function getTotalOutputsWithFees()
    {
        return bcadd($this->totalOutputs, $this->fees, 8);
    }

    /**
     * Set keychain
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Keychain $keychain
     * @return Withdraw
     */
    public function setKeychain(\Dizda\Bundle\AppBundle\Entity\Keychain $keychain)
    {
        $this->keychain = $keychain;

        return $this;
    }

    /**
     * Get keychain
     * @codeCoverageIgnore
     *
     * @return \Dizda\Bundle\AppBundle\Entity\Keychain
     */
    public function getKeychain()
    {
        return $this->keychain;
    }

    /**
     * Add signatures
     * @codeCoverageIgnore
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Identity $signatures
     *
     * @return Withdraw
     */
    public function addSignature(\Dizda\Bundle\AppBundle\Entity\Identity $signatures)
    {
        $this->signatures[] = $signatures;

        return $this;
    }

    /**
     * Remove signatures
     * @codeCoverageIgnore
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Identity $signatures
     */
    public function removeSignature(\Dizda\Bundle\AppBundle\Entity\Identity $signatures)
    {
        $this->signatures->removeElement($signatures);
    }

    /**
     * Get signatures
     * @codeCoverageIgnore
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSignatures()
    {
        return $this->signatures;
    }

    /**
     * Set changeAddressAmount
     *
     * @param string $changeAddressAmount
     * @return Withdraw
     */
    public function setChangeAddressAmount($changeAddressAmount)
    {
        $this->changeAddressAmount = $changeAddressAmount;

        return $this;
    }

    /**
     * Be sure that available inputs is sufficient to spend to outputs
     *
     * @return bool
     */
    public function isSpendable()
    {
        $rest = bcsub($this->getTotalInputs(), $this->getTotalOutputsWithFees(), 8);

        if (bccomp($rest, '0', 8) === -1) {
            return false;
        }

        return true;
    }

    /**
     * Get changeAddressAmount
     *
     * @return string
     */
    public function getChangeAddressAmount()
    {
        return $this->changeAddressAmount;
    }

    /**
     * Set changeAddress
     *
     * @param \Dizda\Bundle\AppBundle\Entity\Address $changeAddress
     * @return Withdraw
     */
    public function setChangeAddress(\Dizda\Bundle\AppBundle\Entity\Address $changeAddress = null)
    {
        $this->changeAddress = $changeAddress;

        return $this;
    }

    /**
     * Get changeAddress
     *
     * @return \Dizda\Bundle\AppBundle\Entity\Address
     */
    public function getChangeAddress()
    {
        return $this->changeAddress;
    }

    /**
     * Setting outputs
     *
     * @param array $outputs
     */
    public function setOutputs(array $outputs)
    {
        foreach ($outputs as $output) {
            $this->addTotalOutputs($output->getAmount());
            $this->addWithdrawOutput($output);
        }
    }

    /**
     * Setting input transactions from Transaction entities
     *
     * @param array $inputs
     */
    public function setInputs(array $inputs)
    {
        foreach ($inputs as $transaction) {
            $this->addTotalInputs($transaction->getAmount());
            $this->addWithdrawInput($transaction);

            // $withdraw->getTotalInputs() >= $withdraw->getTotalOutputs()
            if (bccomp($this->getTotalInputs(), $this->getTotalOutputsWithFees(), 8) !== -1) {
                // if the amount collected is sufficient, we quit the foreach to do not add more inputs
                break;
            }
        }
    }
}

