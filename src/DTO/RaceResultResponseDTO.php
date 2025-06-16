<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\RaceResult;

final class RaceResultResponseDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $driverName,
        public readonly string $teamName,
        public readonly int $position,
        public readonly string $bestLapTime,
        public readonly int $points,
        public readonly bool $isPodium,
        public readonly bool $isWin,
        public readonly string $recordedAt,
    ) {}

    public static function fromEntity(RaceResult $result): self
    {
        return new self(
            id: $result->getId(),
            driverName: $result->getDriver()->getName(),
            teamName: $result->getDriver()->getTeam()->getName(),
            position: $result->getPosition()->getValue(),
            bestLapTime: $result->getBestLap()->toString(),
            points: $result->getPoints()->getValue(),
            isPodium: $result->isPodium(),
            isWin: $result->isWin(),
            recordedAt: $result->getRecordedAt()->format('Y-m-d H:i:s')
        );
    }
}
