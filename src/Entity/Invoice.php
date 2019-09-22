<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvoiceRepository")
 * @ORM\Table(name="invoices")
 * @ORM\HasLifecycleCallbacks()
 */
class Invoice
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var datetime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;


    /**
     * @var datetime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $orderDate;

    /**
     * @var datetime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deliveryDate;

    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="App\Entity\Company")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $carrier;

    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="App\Entity\Company")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $seller;

    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="App\Entity\Company")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $buyer;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $attachedDocument;

    /**
     * @var CompanyAddress
     * @ORM\ManyToOne(targetEntity="App\Entity\CompanyAddress")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $loadingPoint;

    /**
     * @var CompanyAddress
     * @ORM\ManyToOne(targetEntity="App\Entity\CompanyAddress")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $unloadingPoint;

    /**
     * @var CompanyEmployee
     * @ORM\ManyToOne(targetEntity="App\Entity\CompanyEmployee")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $approvedByEmployee;

    /**
     * @var CompanyEmployee
     * @ORM\ManyToOne(targetEntity="App\Entity\CompanyEmployee")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $processedByEmployee;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $recipientName;

    /**
     * @return CompanyEmployee|null
     */
    public function getApprovedByEmployee(): ?CompanyEmployee
    {
        return $this->approvedByEmployee;
    }

    /**
     * @param CompanyEmployee $approvedByEmployee
     * @return Invoice
     */
    public function setApprovedByEmployee(CompanyEmployee $approvedByEmployee): Invoice
    {
        $this->approvedByEmployee = $approvedByEmployee;

        return $this;
    }

    /**
     * @return CompanyEmployee|null
     */
    public function getProcessedByEmployee(): ?CompanyEmployee
    {
        return $this->processedByEmployee;
    }

    /**
     * @param CompanyEmployee $processedByEmployee
     * @return Invoice
     */
    public function setProcessedByEmployee(CompanyEmployee $processedByEmployee): Invoice
    {
        $this->processedByEmployee = $processedByEmployee;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getOrderDate(): ?DateTime
    {
        return $this->orderDate;
    }

    /**
     * @param DateTime $orderDate
     * @return Invoice
     */
    public function setOrderDate(DateTime $orderDate): Invoice
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDeliveryDate(): ?DateTime
    {
        return $this->deliveryDate;
    }

    /**
     * @param DateTime $deliveryDate
     * @return Invoice
     */
    public function setDeliveryDate(DateTime $deliveryDate): Invoice
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    /**
     * @return Company|null
     */
    public function getCarrier(): ?Company
    {
        return $this->carrier;
    }

    /**
     * @param Company $carrier
     * @return Invoice
     */
    public function setCarrier(Company $carrier): Invoice
    {
        $this->carrier = $carrier;

        return $this;
    }

    /**
     * @return Company
     */
    public function getSeller(): Company
    {
        return $this->seller;
    }

    /**
     * @param Company $seller
     * @return Invoice
     */
    public function setSeller(Company $seller): Invoice
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * @return Company
     */
    public function getBuyer(): Company
    {
        return $this->buyer;
    }

    /**
     * @param Company $buyer
     * @return Invoice
     */
    public function setBuyer(Company $buyer): Invoice
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAttachedDocument(): ?string
    {
        return $this->attachedDocument;
    }

    /**
     * @param string $attachedDocument
     * @return Invoice
     */
    public function setAttachedDocument(string $attachedDocument): Invoice
    {
        $this->attachedDocument = $attachedDocument;

        return $this;
    }

    /**
     * @return CompanyAddress|null
     */
    public function getLoadingPoint(): ?CompanyAddress
    {
        return $this->loadingPoint;
    }

    /**
     * @param CompanyAddress $loadingPoint
     * @return Invoice
     */
    public function setLoadingPoint(CompanyAddress $loadingPoint): Invoice
    {
        $this->loadingPoint = $loadingPoint;

        return $this;
    }

    /**
     * @return CompanyAddress|null
     */
    public function getUnloadingPoint(): ?CompanyAddress
    {
        return $this->unloadingPoint;
    }

    /**
     * @param CompanyAddress $unloadingPoint
     * @return Invoice
     */
    public function setUnloadingPoint(CompanyAddress $unloadingPoint): Invoice
    {
        $this->unloadingPoint = $unloadingPoint;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRecipientName(): ?string
    {
        return $this->recipientName;
    }

    /**
     * @param string $recipientName
     * @return Invoice
     */
    public function setRecipientName(string $recipientName): Invoice
    {
        $this->recipientName = $recipientName;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist(): void
    {
        $this->createdAt = new DateTime();
    }
}
