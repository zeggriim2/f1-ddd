<?php

namespace App\Domain\Specification;

/**
 * SPECIFICATION PATTERN : Encapsule les règles métier complexes
 * - Réutilisable
 * - Combinable (AND, OR, NOT)
 * - Testable unitairement
 * - Expressive (code auto-documenté)
 */
interface SpecificationInterface
{
    public function isSatisfiedBy($candidate): bool;
}
