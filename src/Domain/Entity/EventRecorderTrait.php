<?php

namespace App\Domain\Entity;

use App\Domain\Event\DomainEvent;

/**
 * TRAIT : Gestion des événements dans les entités
 */
trait EventRecorderTrait
{
    /** @var DomainEvent[] */
    private array $recordedEvents = [];

    protected function recordEvent(DomainEvent $event): void
    {
        $this->recordedEvents[] = $event;
    }

    /**
     * @return DomainEvent[]
     */
    public function getRecordedEvents(): array
    {
        return $this->recordedEvents;
    }

    public function clearEvents(): void
    {
        $this->recordedEvents = [];
    }
}
