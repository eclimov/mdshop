<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Company::class);
    }

    /**
     * @return Company[]
     */
    public function findVisible(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.hidden = :hidden')
            ->setParameters([
                'hidden' => false,
            ])
            ->orderBy('c.createdAt')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Company[]
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.createdAt')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @return Company[]
     */
    public function findVisibleToUser(User $user): array
    {
        if ($user->getRole() !== 'ROLE_ADMIN') {
            return $this->findVisible();
        }

        return $this->findAll();
    }
}
