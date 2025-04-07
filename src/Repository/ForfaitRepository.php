<?php

namespace App\Repository;

use App\Entity\Forfait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Forfait>
 */
class ForfaitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Forfait::class);
    }

    /**
     * Trouver tous les forfaits d'un partenaire donné
     */
    public function findByPartner(int $partnerId): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.partner = :partnerId')
            ->setParameter('partnerId', $partnerId)
            ->getQuery()
            ->getResult();
    }
}
