<?php

declare(strict_types=1);

namespace App\ValueObject;

class RaceNumber
{
    private int $number;

    public function __construct(int $number)
    {
        $this->validate($number);
        $this->number = $number;
    }

    private function validate(int $number): void
    {
        if ($number < 1 || $number > 99) {
            throw new \InvalidArgumentException('Race number must be between 1 and 99');
        }

        // Numéro 17 retiré en hommage à Jules Bianchi
        if ($number === 17) {
            throw new \InvalidArgumentException('Number 17 is retired');
        }
    }

    public function getValue(): int
    {
        return $this->number;
    }

    public function equals(RaceNumber $other): bool
    {
        return $this->number === $other->number;
    }

    public function __toString(): string
    {
        return (string) $this->number;
    }
}
