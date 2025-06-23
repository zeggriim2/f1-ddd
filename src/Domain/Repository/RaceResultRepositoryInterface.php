<?php

namespace App\Domain\Repository;

use App\Domain\Entity\RaceResult;
use Symfony\Component\Uid\Uuid;

interface RaceResultRepositoryInterface
{
    public function save(RaceResult $result): void;
    public function findByRace(Uuid $raceId): array;
    public function findByDriver(Uuid $driverId): array;
    public function findRaceAndDriver(Uuid $raceId, Uuid $driverId): ?RaceResult;
}
