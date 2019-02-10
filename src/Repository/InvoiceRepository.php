<?php

namespace App\Repository;

use App\Entity\Invoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    /**
     * @return Invoice[]
     */
    public function findAllOrderDesc(): array
    {
        return $this->createQueryBuilder('i')
            ->addOrderBy('i.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
}
