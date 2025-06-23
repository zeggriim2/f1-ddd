<?php

declare(strict_types=1);

namespace App\Infrastructure\EventListener;

use App\Domain\Event\DriverCreated;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;


/**
 * EVENT LISTENERS : Réactions aux événements du domaine
 * - Découplage total du domaine
 * - Effets de bord (email, log, cache, etc.)
 * - Facilement testables
 */
#[AsEventListener]
final class DriverCreatedListener
{
    public function __construct(private LoggerInterface $logger) {}

    public function __invoke(DriverCreated $event): void
    {
        $this->logger->info('New F1 driver created', [
            'driver_id' => (string)$event->driverId,
            'driver_name' => $event->driverName,
            'nationality' => $event->nationality,
            'occurred_on' => $event->occurredOn->format('Y-m-d H:i:s')
        ]);

        // Ici on pourrait :
        // - Envoyer un email de bienvenue
        // - Créer un profil sur les réseaux sociaux
        // - Invalider un cache
        // - Envoyer une notification
    }
}
