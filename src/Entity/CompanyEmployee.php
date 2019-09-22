<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyEmployeeRepository")
 * @ORM\Table(name="company_employees")
 * @ORM\HasLifecycleCallbacks()
 */
class CompanyEmployee
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
    private $name;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $position;

    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="employees")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $company;

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
     * @return CompanyEmployee
     */
    public function setId(int $id): CompanyEmployee
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
     * @return CompanyEmployee
     */
    public function setName(string $name): CompanyEmployee
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    /**
     * @param string|null $position
     * @return CompanyEmployee
     */
    public function setPosition(?string $position): CompanyEmployee
    {
        $this->position = $position;

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
     * @return CompanyEmployee
     */
    public function setCompany(Company $company): CompanyEmployee
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
     * @ORM\PrePersist()
     */
    public function prePersist(): void
    {
        $this->createdAt = new \DateTime();
    }
}
