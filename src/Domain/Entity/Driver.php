<?php

declare(strict_types=1);

namespace App\Domain\Entity;


use App\Domain\Event\DriverCreated;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'drivers')]
class Driver
{
    use EventRecorderTrait;
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    private Uuid $id;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $firstName;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $lastName;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $nationality;

    #[ORM\Column(type: Types::INTEGER, unique: true)]
    private int $number;

    #[ORM\Column(type: Types::INTEGER)]
    private int $raceCount = 0;

    public function __construct(
        Uuid $id,
        string $firstName,
        string $lastName,
        string $nationality,
        int $number
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->nationality = $nationality;
        $this->number = $number;

        // ✅ DOMAIN EVENT : Enregistrement automatique
        $this->recordEvent(new DriverCreated(
            $this->id,
            $this->getFullName(),
            $this->nationality
        ));
    }
    public function getId(): Uuid { return $this->id; }
    public function getFirstName(): string { return $this->firstName; }
    public function getLastName(): string { return $this->lastName; }
    public function getNationality(): string { return $this->nationality; }
    public function getNumber(): int { return $this->number; }
    public function getRaceCount(): int { return $this->raceCount; }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function incrementRaceCount(): void
    {
        $this->raceCount++;
    }

    public function changeName(string $firstName, string $lastName): void
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}
