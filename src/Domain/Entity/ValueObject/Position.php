<?php

declare(strict_types=1);

namespace App\Domain\Entity\ValueObject;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
final readonly class Position
{

    public function __construct(
        #[Column(type: Types::STRING, nullable: true)]
        private int $value
    )
    {
        if ($value < 0 || $value > 20) {
            throw new \InvalidArgumentException('Position must be between 0 and 20');
        }
    }

    public function getValue(): int { return $this->value; }

    // ✅ LOGIQUE MÉTIER : Règles F1 encapsulées
    public function isFirstPlace(): bool
    {
        return $this->value === 1;
    }

    public function isPodium(): bool
    {
        return $this->value <= 3;
    }

    public function isPointsPosition(): bool
    {
        return $this->value <= 10;
    }
}
