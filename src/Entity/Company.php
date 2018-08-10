<?php
namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="companies")
 * @ORM\HasLifecycleCallbacks()
 */
class Company{
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
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $iban;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $fiscalCode;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $vat;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\CompanyAddress", mappedBy="company")
     */
    private $addresses;

    /**
     * @var BankAffiliate
     * @ORM\ManyToOne(targetEntity="App\Entity\BankAffiliate", inversedBy="companies")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $bankAffiliate;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
    }

    /**
     * @param CompanyAddress $address
     * @return Company
     */
    public function addAddress(CompanyAddress $address): Company
    {
        if(!$this->addresses->contains($address)) {
            $this->addresses->add($address);
        }

        return $this;
    }

    /**
     * @param CompanyAddress $address
     * @return Company
     */
    public function removeAddress(CompanyAddress $address): Company
    {
        $this->addresses->removeElement($address);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIban(): ?string
    {
        return $this->iban;
    }

    /**
     * @param string $iban
     * @return Company
     */
    public function setIban(string $iban): Company
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFiscalCode(): ?string
    {
        return $this->fiscalCode;
    }

    /**
     * @param string $fiscalCode
     * @return Company
     */
    public function setFiscalCode(string $fiscalCode): Company
    {
        $this->fiscalCode = $fiscalCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVat(): ?string
    {
        return $this->vat;
    }

    /**
     * @param string $vat
     * @return Company
     */
    public function setVat(string $vat): Company
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * @return Collection|CompanyAddress[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    /**
     * @return BankAffiliate|null
     */
    public function getBankAffiliate(): ?BankAffiliate
    {
        return $this->bankAffiliate;
    }

    /**
     * @param BankAffiliate $bankAffiliate
     * @return Company
     */
    public function setBankAffiliate(BankAffiliate $bankAffiliate): Company
    {
        $this->bankAffiliate = $bankAffiliate;

        return $this;
    }

    /**
     * @var datetime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Company
     */
    public function setId(int $id): Company
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Company
     */
    public function setName(string $name): Company
    {
        $this->name = $name;

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
     * @ORM\PrePersist()
     */
    public function prePersist(): void
    {
        $this->createdAt = new \DateTime();
    }
}
