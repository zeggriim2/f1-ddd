<?php

declare(strict_types=1);

namespace App\ValueObject;

use Webmozart\Assert\Assert;

class Speed
{
    private float $kmh;

    public function __construct(float $kmh)
    {
        $this->validate($kmh);
        $this->kmh = $kmh;
    }

    private function validate(float $kmh): void
    {
        Assert::greaterThanEq($kmh, 0, 'Speed cannot be negative');
        Assert::lessThanEq($kmh, 500, 'Speed cannot be negative');
    }

    public function getKmh(): float
    {
        return $this->kmh;
    }

    public function getMph(): float
    {
        return $this->kmh * 0.621371;
    }

    public function isFasterThan(Speed $other): bool
    {
        return $this->kmh > $other->kmh;
    }

    public function __toString(): string
    {
        return sprintf('%.1f km/h', $this->kmh);
    }
}
