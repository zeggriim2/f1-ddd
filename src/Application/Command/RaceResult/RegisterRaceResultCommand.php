<?php

declare(strict_types=1);

namespace App\Application\Command\RaceResult;

final readonly class RegisterRaceResultCommand
{
    public function __construct(
        public string $raceName,
        public int $driverNumber,
        public int $position,
        public string $bestLapTime
    ) {}
}
