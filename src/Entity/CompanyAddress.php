<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyAddressRepository")
 * @ORM\Table(name="company_addresses")
 * @ORM\HasLifecycleCallbacks()
 */
class CompanyAddress
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="addresses")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $company;

    /**
     * @var datetime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var bool
     * @ORM\Column(name="is_juridic", type="boolean", nullable=false)
     */
    private $juridic;

    public function __construct()
    {
        $this->juridic = false;  // default value
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return CompanyAddress
     */
    public function setId(int $id): CompanyAddress
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return CompanyAddress
     */
    public function setAddress(string $address): CompanyAddress
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Company|null
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * @param Company $company
     * @return CompanyAddress
     */
    public function setCompany(Company $company): CompanyAddress
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return bool
     */
    public function isJuridic(): ?bool
    {
        return $this->juridic;
    }

    /**
     * @param bool $juridic
     * @return CompanyAddress
     */
    public function setJuridic(bool $juridic): CompanyAddress
    {
        $this->juridic = $juridic;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist(): void
    {
        $this->createdAt = new DateTime();
    }
}
