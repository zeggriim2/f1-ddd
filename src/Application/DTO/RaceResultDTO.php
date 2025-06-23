<?php

declare(strict_types=1);

namespace App\Application\DTO;

final class RaceResultDTO
{
    public function __construct(
        public int $position,
        public string $driverFullName,
        public string $bestLapTime,
        public int $points,
        public bool $isPodiumFinish,
    ) {}
}
