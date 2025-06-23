<?php

namespace App\Domain\Event;

interface DomainEventDispatcherInterface
{
    public function dispatch(DomainEvent $event): void;
    public function dispatchAll(array $events): void;
}
