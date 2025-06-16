<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;


final readonly class RecordRaceInputDTO
{
    public function __construct(
        #[Assert\NotBlank(message: 'Driver ID is required')]
        #[Assert\Positive(message: 'Driver ID must be positive')]
        public int    $driverId,

        #[Assert\NotBlank(message: 'Race ID is required')]
        #[Assert\Positive(message: 'Race ID must  be positive')]
        public int    $raceId,

        #[Assert\NotBlank(message: "Position is required")]
        #[Assert\Range(notInRangeMessage: "Position must be between 1 and 20", min: 1, max: 20)]
        public int    $position,

        #[Assert\NotBlank(message: 'Best lap time is required')]
        public string $bestLapTime
    ) {
    }
}
