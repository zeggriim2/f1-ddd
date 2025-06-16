<?php

namespace App\Repository;

use App\Entity\Driver;
use App\Entity\Race;
use App\Entity\RaceResult;
use App\ValueObject\Position;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RaceResult>
 */
class RaceResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RaceResult::class);
    }

    public function findByRaceAndPosition(Race $race, Position $position): ?RaceResult
    {
        return $this->createQueryBuilder('rr')
            ->where('rr.race = :race')
            ->andWhere('rr.position = :position')
            ->setParameter('race', $race)
            ->setParameter('position', $position->getValue())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByRaceAndDriver(Race $race, Driver $driver): ?RaceResult
    {
        return $this->createQueryBuilder('rr')
            ->where('rr.race = :race')
            ->andWhere('rr.driver = :driver')
            ->setParameter('race', $race)
            ->setParameter('driver', $driver)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByRaceOrderedByPosition(Race $race): array
    {
        return $this->createQueryBuilder('rr')
            ->where('rr.race = :race')
            ->setParameter('race', $race)
            ->orderBy('rr.position')
            ->getQuery()
            ->getResult();
    }
}
