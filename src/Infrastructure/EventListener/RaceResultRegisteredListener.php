<?php

declare(strict_types=1);

namespace App\Infrastructure\EventListener;

use App\Domain\Event\RaceResultRegistered;
use Psr\Log\LoggerInterface;

class RaceResultRegisteredListener
{
    public function __construct(private LoggerInterface $logger) {}

    public function __invoke(RaceResultRegistered $event): void
    {
        $this->logger->info('Race result registered', [
            'race_name' => $event->raceName,
            'driver_name' => $event->driverName,
            'position' => $event->position,
            'points' => $event->points,
            'is_podium' => $event->isPodium
        ]);

        // Réactions possibles :
        if ($event->isPodium) {
            // Envoyer notification de podium
            $this->logger->info('Podium finish achieved!', [
                'driver' => $event->driverName,
                'race' => $event->raceName
            ]);
        }

        if ($event->position === 1) {
            // Célébrer la victoire
            $this->logger->info('Victory achieved!', [
                'driver' => $event->driverName,
                'race' => $event->raceName
            ]);
        }
    }
}
