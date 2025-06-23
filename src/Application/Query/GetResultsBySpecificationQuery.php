<?php

declare(strict_types=1);

namespace App\Application\Query;

final readonly class GetResultsBySpecificationQuery
{
    public function __construct(
        public string $raceName,
        public string $specificationType // 'podium', 'points', 'winner'
    ) {}
}
