<?php

declare(strict_types=1);

namespace App\Domain\Specification;

final class OrSpecification implements SpecificationInterface
{
    public function __construct(
        private SpecificationInterface $left,
        private SpecificationInterface $right
    ) {}

    public function isSatisfiedBy($candidate): bool
    {
        return $this->left->isSatisfiedBy($candidate) || $this->right->isSatisfiedBy($candidate);
    }
}
