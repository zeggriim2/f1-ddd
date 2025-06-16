<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class UpdateDriverInputDTO
{

    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int $driverId,

        #[Assert\Length(min: 1, max: 255)]
        public readonly ?string $name = null,

        #[Assert\Length(min: 3, max: 3)]
        public readonly ?string $abbreviation = null,

        #[Assert\Range(min: 1, max: 99)]
        public readonly ?int $raceNumber = null,
    ) {}
}
