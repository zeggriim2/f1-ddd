<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Race;
use Doctrine\DBAL\LockMode;
use Symfony\Component\Uid\Uuid;

interface RaceRepositoryInterface
{
    public function find(mixed $id, LockMode|int|null $lockMode = null, ?int $lockVersion = null): ?object;
    public function findRace(Uuid $id): ?Race;
    public function findAll(): array;
    public function findByName(string $name): ?Race;
    public function save(Race $race): void;
}
