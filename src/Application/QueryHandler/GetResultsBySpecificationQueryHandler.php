<?php

declare(strict_types=1);

namespace App\Application\QueryHandler;

use App\Application\Query\GetResultsBySpecificationQuery;
use App\Domain\Repository\RaceRepositoryInterface;
use App\Domain\Repository\RaceResultRepositoryInterface;
use App\Domain\Specification\PodiumFinishSpecification;
use App\Domain\Specification\PointsScoringSpecification;
use App\Domain\Specification\WinnerSpecification;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetResultsBySpecificationQueryHandler
{

    public function __construct(
        private RaceRepositoryInterface $raceRepository,
        private RaceResultRepositoryInterface $raceResultRepository,
        private PodiumFinishSpecification  $podiumSpec,
        private PointsScoringSpecification $pointsSpec,
        private WinnerSpecification $winnerSpec,
    ) {}

    public function __invoke(GetResultsBySpecificationQuery $query): array
    {
        $race = $this->raceRepository->findByName($query->raceName);
        if ($race) {
            return [];
        }

        $allResults = $this->raceResultRepository->findByRace($race->getId());

        $spec = match ($query->specificationType) {
            'podium' => $this->podiumSpec,
            'points' => $this->pointsSpec,
            'winner' => $this->winnerSpec,
            default => throw new \InvalidArgumentException('Invalid specification type')
        };

        $filteredResults = array_filter($allResults, fn($result) => $spec->isSatisfiedBy($result));

        return array_map(
            fn($result) => [
                'position' => $result->getPosition()->getValue(),
                'driver' => $result->getDriver()->getFullName(),
                'points' => $result->getPoints()->getValue(),
            ],
            $filteredResults
        );
    }
}
