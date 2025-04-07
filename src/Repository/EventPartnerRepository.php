<?php

namespace App\Repository;

use App\Entity\EventPartner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventPartner>
 */
class EventPartnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventPartner::class);
    }

    /**
     * Trouver tous les partenaires d'un événement donné
     */
    public function findByEvent(int $eventId): array
    {
        return $this->createQueryBuilder('ep')
            ->where('ep.event = :eventId')
            ->setParameter('eventId', $eventId)
            ->getQuery()
            ->getResult();
    }
}
