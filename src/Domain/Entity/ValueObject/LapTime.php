<?php

declare(strict_types=1);

namespace App\Domain\Entity\ValueObject;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

/**
 * VALUE OBJECT : Objet immutable qui représente un concept métier
 * Avantages :
 * - Pas d'état interne modifiable (readonly)
 * - Validation à la création
 * - Logique métier encapsulée
 * - Égalité par valeur, pas par référence
 */
#[Embeddable]
final readonly class LapTime
{
    public function __construct(
        #[Column(type: Types::INTEGER)]
        private int $minutes,
        #[Column(type: Types::INTEGER)]
        private int $seconds,
        #[Column(type: Types::INTEGER)]
        private int $milliseconds,
    ) {
        if ($minutes < 0 || $seconds < 0 || $seconds >= 60 || $milliseconds < 0 || $milliseconds >= 1000) {
            throw new \InvalidArgumentException('Invalid lap time format');
        }
    }

    // ✅ LOGIQUE MÉTIER : Comportements dans le Value Object
    public function toTotalMilliseconds(): int
    {
        return ($this->minutes * 60 * 1000) + ($this->seconds * 1000) + $this->milliseconds;
    }

    public function toString(): string
    {
        return sprintf('%d:%02d:%03d', $this->minutes, $this->seconds, $this->milliseconds);
    }

    public function isFastherThan(LapTime $other): bool
    {
        return $this->toTotalMilliseconds() < $other->toTotalMilliseconds();
    }

    // ✅ FACTORY METHOD : Construction depuis une chaîne
    public static function fromString(string $time): self
    {
        if (!preg_match('/^(\d+):(\d{2})\.(\d{3})$/', $time, $matches)) {
            throw new \InvalidArgumentException('Invalid time format. Expected: M:SS.mmm');
        }

        return new self((int)$matches[1], (int)$matches[2], (int)$matches[3]);
    }

    public function getMinutes(): int { return $this->minutes; }
    public function getSeconds(): int { return $this->seconds; }
    public function getMilliseconds(): int { return $this->milliseconds; }
}
