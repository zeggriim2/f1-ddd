<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'races')]
class Race
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    private Uuid $id;

    #[ORM\Column(type: Types::STRING, length: 200)]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 200)]
    private string $circuit;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $date;

    public function __construct(
        Uuid $id,
        string $name,
        string $circuit,
        DateTimeImmutable $date
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->circuit = $circuit;
        $this->date = $date;
    }

    // Getters...
    public function getId(): Uuid { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getCircuit(): string { return $this->circuit; }
    public function getDate(): DateTimeImmutable
    { return $this->date; }

    public function isInFuture(): bool
    {
        return $this->date > new DateTimeImmutable();
    }
}
