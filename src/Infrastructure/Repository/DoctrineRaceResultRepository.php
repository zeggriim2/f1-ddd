<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\RaceResult;
use App\Domain\Event\DomainEventDispatcherInterface;
use App\Domain\Repository\RaceResultRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

final class DoctrineRaceResultRepository extends BaseDoctrineRepository implements RaceResultRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        DomainEventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($registry, RaceResult::class, $eventDispatcher);
    }

    public function save(RaceResult $result): void
    {
        $this->saveWithEvents($result);    }

    public function findByRace(Uuid $raceId): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.race = :raceId')
            ->setParameter('raceId', $raceId->toBinary())
            ->orderBy('r.position.value', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByDriver(Uuid $driverId): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.driver = :driverId')
            ->setParameter('driverId', $driverId)
            ->orderBy('r.position.value', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findRaceAndDriver(Uuid $raceId, Uuid $driverId): ?RaceResult
    {
        return $this->createQueryBuilder('r')
            ->join('r.race', 'race')
            ->join('r.driver', 'driver')
            ->where('race.id = :raceId')
            ->andWhere('driver.id = :driverId')
            ->setParameter('raceId', $raceId)
            ->setParameter('driverId',$driverId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
