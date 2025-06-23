<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\Points;
use App\Domain\Event\ChampionshipStandingsChanged;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * AGGREGATE ROOT : Entité racine qui gère la cohérence d'un ensemble d'entités
 * - Point d'entrée pour les modifications
 * - Gère l'invariant métier
 * - Coordonne les entités enfants
 * - Génère les événements
 */
#[ORM\Entity]
#[ORM\Table(name: 'championships')]
final class Championship
{
    use EventRecorderTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(type: 'integer')]
    private int $season;

    #[ORM\Column(type: 'string', length: 200)]
    private string $name;

    /** @var Race[] */
    #[ORM\OneToMany(targetEntity: Race::class, mappedBy: 'championship')]
    private array $races = [];

    /** @var RaceResult[] */
    private array $results = [];

    public function __construct(Uuid $id, int $season, string $name)
    {
        $this->id = $id;
        $this->season = $season;
        $this->name = $name;
    }

    public function getId(): Uuid { return $this->id; }
    public function getSeason(): int { return $this->season; }
    public function getName(): string { return $this->name; }

    /**
     * ✅ INVARIANT : Une course ne peut être ajoutée que si elle est dans la bonne saison
     */
    public function addRace(Race $race): void
    {
        if ($race->getDate()->format('Y') !== (string)$this->season) {
            throw new \DomainException('Race date must be in championship season');
        }

        $this->races[] = $race;
    }

    /**
     * ✅ COORDINATION : L'agrégat coordonne l'ajout de résultats
     */
    public function addResult(RaceResult $result): void
    {
        // Vérifier que la course fait partie du championnat
        if (!in_array($result->getRace(), $this->races, true)) {
            throw new \DomainException('Race is not part of this championship');
        }

        $this->results[] = $result;

        // Recalculer et vérifier le changement de leader
        $this->checkForLeadershipChange();
    }

    /**
     * ✅ LOGIQUE MÉTIER COMPLEXE : Calcul du classement
     */
    public function calculateDriverStandings(): array
    {
        $standings = [];

        foreach ($this->results as $result) {
            $driverId = (string)$result->getDriver()->getId();

            if (!isset($standings[$driverId])) {
                $standings[$driverId] = [
                    'driver' => $result->getDriver(),
                    'points' => new Points(0),
                    'wins' => 0,
                    'podiums' => 0
                ];
            }

            $standings[$driverId]['points'] = $standings[$driverId]['points']->add($result->getPoints());

            if ($result->isWinner()) {
                $standings[$driverId]['wins']++;
            }

            if ($result->isPodiumFinish()) {
                $standings[$driverId]['podiums']++;
            }
        }

        // Tri par points, puis par victoires
        uasort($standings, function($a, $b) {
            $pointsComparison = $b['points']->getValue() <=> $a['points']->getValue();
            if ($pointsComparison === 0) {
                return $b['wins'] <=> $a['wins'];
            }
            return $pointsComparison;
        });

        return array_values($standings);
    }

    /**
     * ✅ ÉVÉNEMENT : Détection du changement de leader
     */
    private function checkForLeadershipChange(): void
    {
        $standings = $this->calculateDriverStandings();

        if (empty($standings)) {
            return;
        }

        $leader = $standings[0];

        $this->recordEvent(new ChampionshipStandingsChanged(
            $this->id,
            $leader['driver']->getFullName(),
            $leader['points']->getValue()
        ));
    }

    /**
     * ✅ REQUÊTE MÉTIER : Statistiques du championnat
     */
    public function getChampionshipStats(): array
    {
        return [
            'totalRaces' => count($this->races),
            'completedRaces' => count($this->results) / 20, // 20 pilotes par course
            'totalDrivers' => count($this->calculateDriverStandings()),
            'leader' => $this->getCurrentLeader(),
        ];
    }

    private function getCurrentLeader(): ?Driver
    {
        $standings = $this->calculateDriverStandings();
        return empty($standings) ? null : $standings[0]['driver'];
    }
}
