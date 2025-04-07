<?php

namespace App\Repository;

use App\Entity\Partner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Partner>
 */
class PartnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partner::class);
    }

    /**
     * Trouver tous les partenaires par type de service
     */
    public function findByServiceType(string $serviceType): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.serviceType = :serviceType')
            ->setParameter('serviceType', $serviceType)
            ->getQuery()
            ->getResult();
    }
}
