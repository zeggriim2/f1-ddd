<?php

declare(strict_types=1);

namespace App\ValueObject;

use Webmozart\Assert\Assert;

class Position
{
    protected const int MIN = 1;
    protected const int MAX = 20;
    private int $value;

    public function __construct(
        int $position
    ) {
        $this->validate($position);
        $this->value = $position;
    }

    private function validate(int $position): void
    {
        Assert::lengthBetween(
            $position,
            self::MIN,
            self::MAX,
            sprintf('Position must be between %d and %d, got %d',
                self::MIN, self::MAX, $position)
        );
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function isPodium():bool
    {
        return $this->value <= 3;
    }

    public function isWin():bool
    {
        return $this->value === 1;
    }

    public function isPointsPosition(): bool
    {
        return $this->value <= 10;
    }

    public function isAhead(Position $other): bool
    {
        return $this->value >= $other->value;
    }
}
