<?php

declare(strict_types=1);

namespace App\ValueObject;

final class Weather
{
    public function __construct(
        private string $condition,
        private Temperature $temperature,
        private int $humidity, // Pourcentage
        private int $windSpeed  // km/h
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        $validConditions = ['dry', 'wet', 'intermediate', 'variable'];
        if (!in_array($this->condition, $validConditions)) {
            throw new \InvalidArgumentException('Invalid weather condition');
        }

        if ($this->humidity < 0 || $this->humidity > 100) {
            throw new \InvalidArgumentException('Humidity must be between 0 and 100');
        }

        if ($this->windSpeed < 0 || $this->windSpeed > 100) {
            throw new \InvalidArgumentException('Wind speed out of range');
        }
    }

    public function getCondition(): string
    {
        return $this->condition;
    }

    public function getTemperature(): Temperature
    {
        return $this->temperature;
    }

    public function isWet(): bool
    {
        return in_array($this->condition, ['wet', 'intermediate']);
    }

    public function isDry(): bool
    {
        return $this->condition === 'dry';
    }
}
