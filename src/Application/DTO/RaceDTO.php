<?php

declare(strict_types=1);

namespace App\Application\DTO;

final class RaceDTO
{
    public function __construct(
        public string $name,
        public string $circuit,
        public string $date,
    ) {}
}
