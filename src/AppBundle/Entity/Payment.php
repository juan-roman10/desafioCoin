<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Payment
 *
 * @ORM\Table(name="payments")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PaymentRepository")
 */
class Payment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="payment_date", type="datetimetz")
     * @Assert\NotBlank
     */
    private $paymentDate;

    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="Company")
     * @Assert\NotBlank
     */
    private $company;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     * @Assert\NotNull
     */
    private $amount;

    /**
     * @var Payment_Method
     * @ORM\ManyToOne(targetEntity="Payment_Method")
     * @Assert\NotNull
     */
    private $paymentMethod;

    /**
     * @var string
     *
     * @ORM\Column(name="external_reference", type="string", length=255, unique=true)
     * @Assert\NotBlank
     */
    private $externalReference;

    /**
     * @var int
     *
     * @ORM\Column(name="terminal", type="integer")
     * @Assert\NotNull
     */
    private $terminal;

    /**
     * @var Status
     * @ORM\ManyToOne(targetEntity="Status")
     * @Assert\NotBlank
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255)
     * @Assert\NotBlank
     */
    private $reference;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set paymentDate
     *
     * @param \DateTime $paymentDate
     *
     * @return Payment
     */
    public function setPaymentDate($paymentDate)
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    /**
     * Get paymentDate
     *
     * @return \DateTime
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    /**
     * Set company
     *
     * @param Company $company
     * @return Company
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return Payment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set paymentMethod
     *
     * @param Payment_Method $paymentMethod
     * @return Payment_Method
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * Get paymentMethod
     *
     * @return Payment_Method
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Set externalReference
     *
     * @param string $externalReference
     *
     * @return Payment
     */
    public function setExternalReference($externalReference)
    {
        $this->externalReference = $externalReference;

        return $this;
    }

    /**
     * Get externalReference
     *
     * @return string
     */
    public function getExternalReference()
    {
        return $this->externalReference;
    }

    /**
     * Set terminal
     *
     * @param integer $terminal
     *
     * @return Payment
     */
    public function setTerminal($terminal)
    {
        $this->terminal = $terminal;

        return $this;
    }

    /**
     * Get terminal
     *
     * @return int
     */
    public function getTerminal()
    {
        return $this->terminal;
    }

    /**
     * Set statusId
     *
     * @param Status $status
     * @return Status
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get statusId
     *
     * @return Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Payment
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }
}

