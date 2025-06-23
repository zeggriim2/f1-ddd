<?php

declare(strict_types=1);

namespace App\Domain\Entity\ValueObject;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
final readonly class Points
{

    private const array POINTS_SYSTEM = [
        1 => 25, 2 => 18, 3 => 15, 4 => 12, 5 => 10,
        6 => 8, 7 => 6, 8 => 4, 9 => 2, 10 => 1
    ];

    public function __construct(
        #[Column(type: 'integer')]
        private int $value
    )
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('Points cannot be negative');
        }
    }

    public function getValue(): int { return $this->value; }

    public static function fromPosition(Position $position): self
    {
        return new self(self::POINTS_SYSTEM[$position->getValue()]);
    }

    public function add(Points $other): self
    {
        return new self($this->value + $other->getValue());
    }
}
