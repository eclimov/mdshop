<?php
namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 * @ORM\Table(name="companies")
 * @UniqueEntity(fields="name", message="This company already exists")
 * @UniqueEntity(fields="shortName", message="This company already exists")
 * @ORM\HasLifecycleCallbacks()
 */
class Company {
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
     * @ORM\Column(type="string", length=40)
     */
    private $shortName;

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
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\CompanyEmployee", mappedBy="company")
     */
    private $employees;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="company")
     */
    private $users;

    /**
     * @var BankAffiliate
     * @ORM\ManyToOne(targetEntity="App\Entity\BankAffiliate", inversedBy="companies")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $bankAffiliate;

    /**
     * @var bool
     * @ORM\Column(name="is_hidden", type="boolean", nullable=false)
     */
    private $hidden;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->employees = new ArrayCollection();
        $this->hidden = false;  // default value
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
     * @param CompanyEmployee $employee
     * @return Company
     */
    public function addEmployee(CompanyEmployee $employee): Company
    {
        if(!$this->employees->contains($this->employees)) {
            $this->employees->add($employee);
        }

        return $this;
    }

    /**
     * @param User $user
     * @return Company
     */
    public function addUser(User $user): Company
    {
        if(!$this->users->contains($user)) {
            $this->users->add($user);
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
     * @param CompanyEmployee $employee
     * @return Company
     */
    public function removeEmployee(CompanyEmployee $employee): Company
    {
        $this->employees->removeElement($employee);
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
     * @return Collection|CompanyEmployee[]
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
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
     * @return string|null
     */
    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    /**
     * @param string $shortName
     * @return Company
     */
    public function setShortName(string $shortName): Company
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return $this->hidden;
    }

    /**
     * @param bool $hidden
     * @return Company
     */
    public function setHidden(bool $hidden)
    {
        $this->hidden = $hidden;

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
