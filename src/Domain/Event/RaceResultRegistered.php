<?php

declare(strict_types=1);

namespace App\Domain\Event;

use Symfony\Component\Uid\Uuid;

final readonly class RaceResultRegistered extends DomainEvent
{
    public function __construct(
        public Uuid $raceId,
        public Uuid $driverId,
        public string $raceName,
        public string $driverName,
        public int $position,
        public int $points,
        public bool $isPodium,
        \DateTimeImmutable $occurredOn = new \DateTimeImmutable(),
    )
    {
        parent::__construct($occurredOn);
    }
}
