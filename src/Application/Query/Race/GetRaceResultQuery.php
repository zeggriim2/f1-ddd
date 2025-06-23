<?php

declare(strict_types=1);

namespace App\Application\Query\Race;

final class GetRaceResultQuery
{
    public function __construct(
        public string $raceName
    ) {}
}
