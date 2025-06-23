<?php

declare(strict_types=1);

namespace App\Domain\Specification;

use App\Domain\Entity\RaceResult;

final class PointsScoringSpecification implements SpecificationInterface
{
    public function isSatisfiedBy($candidate): bool
    {
        if (!$candidate instanceof RaceResult) {
            return false;
        }

        return $candidate->getPosition()->isPointsPosition();
    }
}
