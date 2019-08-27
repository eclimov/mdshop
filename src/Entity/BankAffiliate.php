<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="bank_affiliates")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"affiliateNumber", "bank"},
 *     errorPath="affiliateNumber",
 *     message="This affiliate number already exists in this bank"
 * )
 */
class BankAffiliate
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var Bank
     * @ORM\ManyToOne(targetEntity="App\Entity\Bank", inversedBy="affiliates", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $bank;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Company", mappedBy="bankAffiliate")
     */
    private $companies;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $affiliateNumber;

    /**
     * @var datetime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    public function __construct()
    {
        $this->companies = new ArrayCollection();
    }

    /**
     * @param Company $company
     * @return BankAffiliate
     */
    public function addCompany(Company $company): BankAffiliate
    {
        if(!$this->companies->contains($company)) {
            $this->companies->add($company);
        }

        return $this;
    }

    /**
     * @param Company $company
     * @return BankAffiliate
     */
    public function removeCompany(Company $company): BankAffiliate
    {
        $this->companies->removeElement($company);
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
     * @param int $id
     * @return BankAffiliate
     */
    public function setId(int $id): BankAffiliate
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Bank
     */
    public function getBank(): Bank
    {
        return $this->bank;
    }

    /**
     * @param Bank $bank
     * @return BankAffiliate
     */
    public function setBank(Bank $bank): BankAffiliate
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * @return Collection|Company[]
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    /**
     * @return string|null
     */
    public function getAffiliateNumber(): ?string
    {
        return $this->affiliateNumber;
    }

    /**
     * @param string $affiliateNumber
     * @return BankAffiliate
     */
    public function setAffiliateNumber(string $affiliateNumber): BankAffiliate
    {
        $this->affiliateNumber = $affiliateNumber;

        return $this;
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
