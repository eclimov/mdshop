<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\CompanyEmployee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CompanyEmployeeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CompanyEmployee::class);
    }

    /**
     * @param Company $company
     * @return QueryBuilder
     */
    public function getCompanyEmployeesOrderDirectorLastQb(Company $company): QueryBuilder
    {
        return $this->createQueryBuilder('ce')
            ->addSelect('ce')
            ->addSelect('CASE WHEN ce.position = :director_position THEN 1 ELSE 0 END AS HIDDEN ord')
            ->where('ce.company = :company')
            ->setParameters([
                'company' => $company,
                'director_position' => 'Director',
            ])
            ->orderBy('ord', 'ASC')
        ;
    }
}
