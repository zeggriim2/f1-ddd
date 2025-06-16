<?php

declare(strict_types=1);

namespace App\Entity;

use App\ValueObject\Points;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\TeamRepository")]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 50)]
    private string $color;

    #[ORM\OneToMany(targetEntity: Driver::class, mappedBy: 'team')]
    private Collection $drivers;

    public function __construct(string $name, string $color)
    {
        $this->name = $name;
        $this->color = $color;
        $this->drivers = new ArrayCollection();
    }

    public function getTotalPoints(): Points
    {
        $totalPoints = 0;
        foreach ($this->drivers as $driver) {
            $totalPoints += $driver->getTotalPoints()->getValue();
        }
        return new Points($totalPoints);
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getColor(): string { return $this->color; }
    public function getDrivers(): Collection { return $this->drivers; }
}
