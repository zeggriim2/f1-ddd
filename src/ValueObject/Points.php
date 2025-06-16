<?php

declare(strict_types=1);

namespace App\ValueObject;

use Webmozart\Assert\Assert;

final class Points
{
    private int $value;

    // Système de points F1 actuel
    private const array RACE_POINTS = [
        1 => 25,
        2 => 18,
        3 => 15,
        4 => 12,
        5 => 10,
        6 => 8,
        7 => 6,
        8 => 4,
        9 => 2,
        10 => 1,
    ];

    // Points spéciaux
    private const int FASTEST_LAP_POINTS = 1;
    private const array SPRINT_POINTS = [1 => 8, 2 => 7, 3 => 6, 4 => 5, 5 => 4, 6 => 3, 7 => 2, 8 => 1];

    public function __construct(int $points)
    {
        Assert::greaterThanEq($points, 0, 'Points cannot be negative');

        $this->value = $points;
    }

    public static function fromRacePosition(Position $position): self
    {
        $points = self::RACE_POINTS[$position->getValue()] ?? 0;
        return new self($points);
    }

    public static function fromSprintPosition(Position $position): self
    {
        $points = self::SPRINT_POINTS[$position->getValue()] ?? 0;
        return new self($points);
    }

    public static function fastesLap(): self
    {
        return new self(self::FASTEST_LAP_POINTS);
    }

    public static function zero(): self
    {
        return new self(0);
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function add(Points $other): self
    {
        return new self($this->value + $other->value);
    }

    public function isGreaterThan(Points $other): bool
    {
        return $this->value > $other->getValue();
    }

    public function equals(Points $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
