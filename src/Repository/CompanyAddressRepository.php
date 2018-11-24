<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\CompanyAddress;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CompanyAddressRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CompanyAddress::class);
    }

    /**
     * @param Company $company
     * @return CompanyAddress[]
     */
    public function findJuridicAddressesByCompany(Company $company): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.company = :company')
            ->andWhere('a.juridic = :juridic')
            ->setParameters([
                'company' => $company,
                'juridic' => true,
            ])
            ->getQuery()
            ->getResult();
    }
}
