<?php

declare(strict_types=1);

namespace App\ValueObject;

final class Temperature
{
    private float $celsius;

    public function __construct(float $celsius)
    {
        $this->validate($celsius);
        $this->celsius = $celsius;
    }

    private function validate(float $celsius): void
    {
        if ($celsius < -20 || $celsius > 60) {
            throw new \InvalidArgumentException('Temperature out of realistic range for F1');
        }
    }

    public function getCelsius(): float
    {
        return $this->celsius;
    }

    public function getFahrenheit(): float
    {
        return ($this->celsius * 9/5) + 32;
    }

    public function isHot(): bool
    {
        return $this->celsius > 30;
    }

    public function isCold(): bool
    {
        return $this->celsius < 10;
    }

    public function __toString(): string
    {
        return sprintf('%.1f°C', $this->celsius);
    }
}
