<?php

declare(strict_types=1);

namespace App\Application\DTO;

final class DriverDTO
{
    public function __construct(
        public string $fullName,
        public string $nationality,
        public int $number,
    ) {}
}
