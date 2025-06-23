<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\LapTime;
use App\Domain\Entity\ValueObject\Points;
use App\Domain\Entity\ValueObject\Position;
use App\Domain\Event\RaceResultRegistered;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'race_results')]
class RaceResult
{
    use EventRecorderTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Race::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Race $race;

    #[ORM\ManyToOne(targetEntity: Driver::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Driver $driver;

    #[ORM\Embedded(class: Position::class)]
    private Position $position;

    #[ORM\Embedded(class: LapTime::class)]
    private LapTime $bestLapTime;

    #[ORM\Embedded(class: Points::class)]
    private Points $points;

    public function __construct(
        Uuid $id,
        Race $race,
        Driver $driver,
        Position $position,
        LapTime $bestLapTime,
        Points $points
    ) {
        $this->id = $id;
        $this->race = $race;
        $this->driver = $driver;
        $this->position = $position;
        $this->bestLapTime = $bestLapTime;
        $this->points = $points;

        // ✅ DOMAIN EVENT : Résultat enregistré
        $this->recordEvent(new RaceResultRegistered(
            $race->getId(),
            $driver->getId(),
            $race->getName(),
            $driver->getFullName(),
            $position->getValue(),
            $points->getValue(),
            $position->isPodium()
        ));

        // Incrémenter le compteur de courses du pilote
        $driver->incrementRaceCount();
    }

    // Getters...
    public function getId(): Uuid { return $this->id; }
    public function getRace(): Race { return $this->race; }
    public function getDriver(): Driver { return $this->driver; }
    public function getPosition(): Position { return $this->position; }
    public function getBestLapTime(): LapTime { return $this->bestLapTime; }
    public function getPoints(): Points { return $this->points; }

    public function isWinner(): bool
    {
        return $this->position->isFirstPlace();
    }

    public function isPodiumFinish(): bool
    {
        return $this->position->isPodium();
    }
}
