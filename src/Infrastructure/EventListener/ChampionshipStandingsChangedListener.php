<?php

declare(strict_types=1);

namespace App\Infrastructure\EventListener;

use App\Domain\Event\ChampionshipStandingsChanged;
use Psr\Log\LoggerInterface;

final class ChampionshipStandingsChangedListener
{
    public function __construct(private LoggerInterface $logger) {}

    public function __invoke(ChampionshipStandingsChanged $event): void
    {
        $this->logger->info('Championship standings updated', [
            'championship_id' => (string)$event->championshipId,
            'new_leader' => $event->newLeader,
            'leader_points' => $event->leaderPoints
        ]);

        // Invalider le cache des classements
        // Envoyer notification aux fans
        // Mettre à jour les statistiques en temps réel
    }
}
