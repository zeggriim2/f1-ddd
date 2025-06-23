<?php

declare(strict_types=1);

namespace App\Domain\Event;

/**
 * DOMAIN EVENT : Représente quelque chose qui s'est passé dans le domaine
 * - Immutable (readonly)
 * - Contient les données nécessaires pour les réactions
 * - Permet le découplage (un événement, plusieurs réactions)
 */
abstract readonly class DomainEvent
{
    public function __construct(
        public \DateTimeImmutable $occurredOn = new \DateTimeImmutable(),
    ) {}
}
