<?php

declare(strict_types=1);

namespace App\Domain\Event;

use Symfony\Component\Uid\Uuid;

final readonly class ChampionshipStandingsChanged extends DomainEvent
{
    public function __construct(
        public Uuid $championshipId,
        public string $newLeader,
        public int $leaderPoints,
        \DateTimeImmutable $occurredOn = new \DateTimeImmutable(),
    )
    {
        parent::__construct($occurredOn);
    }
}
