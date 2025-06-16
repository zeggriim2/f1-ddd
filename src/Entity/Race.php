<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\RaceRepository")]
class Race
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $circuit;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $date;

    #[ORM\Column]
    private int $totalLaps;

    #[ORM\OneToMany(mappedBy: 'race', targetEntity: RaceResult::class)]
    private Collection $results;

    public function __construct(string $name, string $circuit, \DateTimeInterface $date, int $totalLaps)
    {
        $this->name = $name;
        $this->circuit = $circuit;
        $this->date = $date;
        $this->totalLaps = $totalLaps;
        $this->results = new ArrayCollection();
    }

    public function isFinished(): bool
    {
        return $this->results->count() > 0;
    }

    public function getWinner(): ?Driver
    {
        foreach ($this->results as $result) {
            if ($result->getPosition()->isWin()) {
                return $result->getDriver();
            }
        }
        return null;
    }

    public function getFastestLap(): ?RaceResult
    {
        $fastest = null;
        foreach ($this->results as $result) {
            if ($fastest === null || $result->getBestLap()->isFasterThan($fastest->getBestLap())) {
                $fastest = $result;
            }
        }
        return $fastest;
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getCircuit(): string { return $this->circuit; }
    public function getDate(): \DateTimeInterface { return $this->date; }
    public function getTotalLaps(): int { return $this->totalLaps; }
    public function getResults(): Collection { return $this->results; }
}
