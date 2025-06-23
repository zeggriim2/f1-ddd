<?php

declare(strict_types=1);

namespace App\Domain\Specification;

use App\Domain\Entity\Driver;

final class ExperiencedDriverSpecification implements SpecificationInterface
{
    public function __construct(private readonly int $minimumRace = 50) {}

    public function isSatisfiedBy($candidate): bool
    {
        if (!$candidate instanceof Driver) {
            return false;
        }

        // Cette logique serait implémentée avec le nombre de courses du pilote
        return $candidate->getRaceCount() >= $this->minimumRace;
    }
}
