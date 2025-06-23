<?php

declare(strict_types=1);

namespace App\Domain\Event;

use Symfony\Component\Uid\Uuid;

final readonly class DriverCreated extends DomainEvent
{
    public function __construct(
        public Uuid $driverId,
        public string $driverName,
        public string $nationality,
        \DateTimeImmutable $occurredOn = new \DateTimeImmutable(),
    )
    {
        parent::__construct($occurredOn);
    }
}
