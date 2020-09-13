<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $paymentDate;

    /**
     * @var int
     *
     * @ORM\Column(name="company_id", type="integer")
     */
    private $companyId;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var int
     *
     * @ORM\Column(name="payment_method_id", type="integer")
     */
    private $paymentMethodId;

    /**
     * @var string
     *
     * @ORM\Column(name="external_reference", type="string", length=255, unique=true)
     */
    private $externalReference;

    /**
     * @var int
     *
     * @ORM\Column(name="terminal", type="integer")
     */
    private $terminal;

    /**
     * @var int
     *
     * @ORM\Column(name="status_id", type="integer")
     */
    private $statusId;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255)
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
     * Set companyId
     *
     * @param integer $companyId
     *
     * @return Payment
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;

        return $this;
    }

    /**
     * Get companyId
     *
     * @return int
     */
    public function getCompanyId()
    {
        return $this->companyId;
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
     * Set paymentMethodId
     *
     * @param integer $paymentMethodId
     *
     * @return Payment
     */
    public function setPaymentMethodId($paymentMethodId)
    {
        $this->paymentMethodId = $paymentMethodId;

        return $this;
    }

    /**
     * Get paymentMethodId
     *
     * @return int
     */
    public function getPaymentMethodId()
    {
        return $this->paymentMethodId;
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
     * @param integer $statusId
     *
     * @return Payment
     */
    public function setStatusId($statusId)
    {
        $this->statusId = $statusId;

        return $this;
    }

    /**
     * Get statusId
     *
     * @return int
     */
    public function getStatusId()
    {
        return $this->statusId;
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

