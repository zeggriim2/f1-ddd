<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Driver;
use App\Entity\Race;
use App\Entity\RaceResult;
use App\ValueObject\LapTime;
use App\ValueObject\Position;

final class RaceResultFactory
{
    public static function create(
        Race $race,
        Driver $driver,
        Position $position,
        LapTime $bestLap,
    )
    {
        $raceResult = new RaceResult();
    }
}
