<?php

declare(strict_types=1);

namespace App\ValueObject;

use Webmozart\Assert\Assert;

class FuelLoad
{
    private float $kilograms;

    public function __construct(float $kilograms)
    {
        $this->validate($kilograms);
        $this->kilograms = $kilograms;
    }

    private function validate(float $kilograms): void
    {
        Assert::greaterThanEq($kilograms, 0, 'Fuel load cannot be negative');

        // Limite réglementaire F1
        Assert::lessThanEq($kilograms, 110, 'Fuel load exceeds F1 limit');
    }

    public function getKilograms(): float
    {
        return $this->kilograms;
    }

    public function isEmpty(): bool
    {
        return $this->kilograms < 1;
    }

    public function isFull(): bool
    {
        return $this->kilograms > 100;
    }

    public function consume(float $amount): self
    {
        $newLoad = max(0, $this->kilograms - $amount);
        return new self($newLoad);
    }

    public function __toString(): string
    {
        return sprintf('%.1f kg', $this->kilograms);
    }
}
