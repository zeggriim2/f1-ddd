<?php

declare(strict_types=1);

namespace App\ValueObject;

final class GridPosition extends Position
{
    // Grille peut avoir des positions spéciales
    private const MAX_GRID_POSITION = 24; // Incluant les réserves
    public function __construct(int $position)
    {
        if ($position < self::MIN || $position > self::MAX_GRID_POSITION) {
            throw new \InvalidArgumentException(
                sprintf('Grid position must be between %d and %d',
                    self::MAX, self::MAX_GRID_POSITION)
            );
        }
        $this->value = $position;
    }
}
