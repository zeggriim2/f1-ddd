<?php

namespace App\Entity;

use App\Repository\RaceResultRepository;
use App\ValueObject\LapTime;
use App\ValueObject\Points;
use App\ValueObject\Position;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RaceResultRepository::class)]
class RaceResult
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Race::class, inversedBy: 'results')]
    #[ORM\JoinColumn(nullable: false)]
    private Race $race;

    #[ORM\ManyToOne(targetEntity: Driver::class, inversedBy: 'raceResults')]
    #[ORM\JoinColumn(nullable: false)]
    private Driver $driver;

    #[ORM\Column]
    private int $position;

    #[ORM\Column]
    private int $bestLapMs;

    #[ORM\Column]
    private int $points;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $recordedAt;

    public function __construct()
    {
//        $this->race = $race;
//        $this->driver = $driver;
//        $this->position = $position->getValue();
//        $this->bestLapMs = $bestLap->getMilliseconds();
//        $this->points = Points::fromRacePosition($position)->getValue();
//        $this->recordedAt = new \DateTime();
    }

    // Méthodes retournant des Value Objects
    public function getPosition(): Position
    {
        return new Position($this->position);
    }

    public function getBestLap(): LapTime
    {
        return LapTime::fromMilliseconds($this->bestLapMs);
    }

    public function getPoints(): Points
    {
        return new Points($this->points);
    }

    // Méthodes métier
    public function isWin(): bool
    {
        return $this->getPosition()->isWin();
    }

    public function isPodium(): bool
    {
        return $this->getPosition()->isPodium();
    }

    public function earnedPoints(): bool
    {
        return $this->getPosition()->isPointsPosition();
    }

    // Getters classiques
    public function getId(): ?int { return $this->id; }
    public function getRace(): Race { return $this->race; }
    public function getDriver(): Driver { return $this->driver; }
    public function getRecordedAt(): DateTimeInterface { return $this->recordedAt; }
}
