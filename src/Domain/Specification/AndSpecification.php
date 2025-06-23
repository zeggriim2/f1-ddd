<?php

declare(strict_types=1);

namespace App\Domain\Specification;

final class AndSpecification implements SpecificationInterface
{
    public function __construct(
        private readonly SpecificationInterface $left,
        private readonly SpecificationInterface $right
    ) {}
    public function isSatisfiedBy($candidate): bool
    {
        return $this->left->isSatisfiedBy($candidate) && $this->right->isSatisfiedBy($candidate);
    }
}
