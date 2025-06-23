<?php

declare(strict_types=1);

namespace App\Application\Command\Driver;

final readonly class CreateDriverCommand
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $nationality,
        public int $number,
    ) {}
}
