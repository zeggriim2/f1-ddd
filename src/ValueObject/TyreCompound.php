<?php

declare(strict_types=1);

namespace App\ValueObject;

use Webmozart\Assert\Assert;

final class TyreCompound
{
    private string $compound;
    private const VALID_COMPOUNDS = ['soft', 'medium', 'hard', 'intermediate', 'wet'];

    public function __construct(string $compound)
    {
        $compound = strtolower($compound);
        Assert::inArray($compound, self::VALID_COMPOUNDS, 'Invalid tyre compound');
        $this->compound = $compound;
    }

    public function getCompound(): string
    {
        return $this->compound;
    }

    public function isDryCompound(): bool
    {
        return in_array($this->compound, ['soft', 'medium', 'hard']);
    }

    public function isWetCompound(): bool
    {
        return in_array($this->compound, ['intermediate', 'wet']);
    }

    public function getColor(): string
    {
        return match($this->compound) {
            'soft' => 'red',
            'medium' => 'yellow',
            'hard' => 'white',
            'intermediate' => 'green',
            'wet' => 'blue'
        };
    }

    public function equals(TyreCompound $other): bool
    {
        return $this->compound === $other->compound;
    }

    public function __toString(): string
    {
        return ucfirst($this->compound);
    }
}
