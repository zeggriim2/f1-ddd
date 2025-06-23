<?php

declare(strict_types=1);

namespace App\Infrastructure\Event;

use App\Domain\Event\DomainEvent;
use App\Domain\Event\DomainEventDispatcherInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class SymfonyDomainEventDispatcher implements DomainEventDispatcherInterface
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher
    ) {}

    public function dispatch(DomainEvent $event): void
    {
        $this->eventDispatcher->dispatch($event);
    }

    public function dispatchAll(array $events): void
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }
}
