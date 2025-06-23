<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Driver;
use App\Domain\Entity\Race;
use App\Domain\Event\DomainEventDispatcherInterface;
use App\Domain\Repository\RaceRepositoryInterface;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

final class DoctrineRaceRepository extends BaseDoctrineRepository implements RaceRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        DomainEventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($registry, Race::class, $eventDispatcher);
    }

    public function find(mixed $id, int|null|LockMode $lockMode = null, ?int $lockVersion = null): ?Race
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['date' => 'ASC']);
    }

    public function findByName(string $name): ?Race
    {
        return $this->findOneBy(['name' => $name]);
    }

    public function save(Race $race): void
    {
        $this->saveWithEvents($race);
    }

    public function findRace(Uuid $id): ?Race
    {
        return  $this->findOneBy(['id' => $id]);
    }
}
