<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Driver;
use Doctrine\DBAL\LockMode;
use Symfony\Component\Uid\Uuid;

interface DriverRepositoryInterface
{
    public function find(mixed $id, LockMode|int|null $lockMode = null, ?int $lockVersion = null): ?object;
    public function findDriver(Uuid $id): ?Driver;
    public function findAll(): array;
    public function findByNumber(int $number): ?Driver;
    public function save(Driver $driver): void;
}
