<?php

declare(strict_types=1);

namespace App\Entity;

use App\ValueObject\Points;
use App\ValueObject\RaceNumber;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\DriverRepository")]
class Driver
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 3)]
    private string $abbreviation;

    #[ORM\Column]
    private int $raceNumber;

    #[ORM\ManyToOne(targetEntity: Team::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Team $team;

    #[ORM\OneToMany(targetEntity: RaceResult::class, mappedBy: 'driver')]
    private Collection $raceResults;

    public function __construct(string $name, string $abbreviation, RaceNumber $raceNumber, Team $team)
    {
        $this->name = $name;
        $this->abbreviation = $abbreviation;
        $this->raceNumber = $raceNumber->getValue(); // Stocké comme int en DB
        $this->team = $team;
        $this->raceResults = new ArrayCollection();
    }

    // Méthodes métier utilisant les Value Objects
    public function getRaceNumber(): RaceNumber
    {
        return new RaceNumber($this->raceNumber);
    }

    public function getTotalPoints(): Points
    {
        $totalPoints = 0;
        foreach ($this->raceResults as $result) {
            $totalPoints += $result->getPoints()->getValue();
        }
        return new Points($totalPoints);
    }

    public function hasWonRace(): bool
    {
        foreach ($this->raceResults as $result) {
            if ($result->getPosition()->isWin()) {
                return true;
            }
        }
        return false;
    }

    public function getPodiumCount(): int
    {
        $podiums = 0;
        foreach ($this->raceResults as $result) {
            if ($result->getPosition()->isPodium()) {
                $podiums++;
            }
        }
        return $podiums;
    }

    // Getters classiques
    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getAbbreviation(): string { return $this->abbreviation; }
    public function getTeam(): Team { return $this->team; }
    public function getRaceResults(): Collection { return $this->raceResults; }
}
