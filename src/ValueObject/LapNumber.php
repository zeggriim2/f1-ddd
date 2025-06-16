<?php

declare(strict_types=1);

namespace App\ValueObject;

use Webmozart\Assert\Assert;

final class LapNumber
{
    private readonly int $lap;
    private readonly int $totalLaps;

    public function __construct(int $lap, int $totalLaps)
    {
        $this->validate($lap, $totalLaps);
        $this->lap = $lap;
        $this->totalLaps = $totalLaps;
    }

    private function validate(int $lap, int $totalLaps): void
    {
        if ($lap < 1 || $totalLaps < 1) {
            throw new \InvalidArgumentException('Lap numbers must be positive');
        }

        Assert::lessThanEq($lap, $totalLaps, 'Lap number cannot exceed total laps');
    }

    public function getLap(): int
    {
        return $this->lap;
    }

    public function getTotalLaps(): int
    {
        return $this->totalLaps;
    }

    public function isFirstLap(): bool
    {
        return $this->lap === 1;
    }

    public function isLastLap(): bool
    {
        return $this->lap === $this->totalLaps;
    }

    public function getProgress(): float
    {
        return ($this->lap / $this->totalLaps) * 100;
    }

    public function next(): self
    {
        if ($this->isLastLap()) {
            throw new \InvalidArgumentException('Cannot go beyond last lap');
        }

        return new self($this->lap + 1, $this->totalLaps);
    }

    public function __toString(): string
    {
        return sprintf('%d/%d', $this->lap, $this->totalLaps);
    }
}
