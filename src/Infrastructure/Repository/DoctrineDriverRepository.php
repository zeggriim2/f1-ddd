<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Driver;
use App\Domain\Event\DomainEventDispatcherInterface;
use App\Domain\Repository\DriverRepositoryInterface;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

final class DoctrineDriverRepository extends BaseDoctrineRepository implements DriverRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        DomainEventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($registry, Driver::class, $eventDispatcher);
    }

    public function find(mixed $id, LockMode|int|null $lockMode = null, ?int $lockVersion = null): ?object
    {
        return parent::find($id, $lockMode, $lockVersion);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['number' => 'ASC']);
    }

    public function findByNumber(int $number): ?Driver
    {
        return $this->findOneBy(['number' => $number]);
    }

    public function save(Driver $driver): void
    {
        $this->saveWithEvents($driver);
    }

    public function findDriver(Uuid $id): ?Driver
    {
        return $this->findOneBy(['id' => $id]);
    }
}
