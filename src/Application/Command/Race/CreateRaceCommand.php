<?php

declare(strict_types=1);

namespace App\Application\Command\Race;

final readonly class CreateRaceCommand
{
    public function __construct(
        public string $name,
        public string $circuit,
        public string $date,
    ) {}
}
