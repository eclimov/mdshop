<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="banks")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"name"},
 *     errorPath="name",
 *     message="This bank already exists in DB"
 * )
 */
class Bank
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
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\BankAffiliate", mappedBy="bank", cascade={"remove"})
     */
    private $affiliates;

    /**
     * @var datetime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    public function __construct()
    {
        $this->affiliates= new ArrayCollection();
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
     * @return Bank
     */
    public function setId(int $id): Bank
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
     * @return Bank
     */
    public function setName(string $name): Bank
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|BankAffiliate[]
     */
    public function getAffiliates(): Collection
    {
        return $this->affiliates;
    }

    /**
     * @param BankAffiliate $affiliate
     * @return Bank
     */
    public function addCompany(BankAffiliate $affiliate): Bank
    {
        if(!$this->affiliates->contains($affiliate)) {
            $this->affiliates->add($affiliate);
        }

        return $this;
    }

    /**
     * @param BankAffiliate $affiliate
     * @return Bank
     */
    public function removeAffiliate(BankAffiliate $affiliate): Bank
    {
        $this->affiliates->removeElement($affiliate);
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
        $this->createdAt = new \DateTime();
    }
}
