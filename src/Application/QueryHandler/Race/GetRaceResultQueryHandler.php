<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\Race;


use App\Application\DTO\RaceResultDTO;
use App\Application\Query\Race\GetRaceResultQuery;
use App\Domain\Entity\RaceResult;
use App\Domain\Repository\RaceRepositoryInterface;
use App\Domain\Repository\RaceResultRepositoryInterface;
use InvalidArgumentException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetRaceResultQueryHandler
{
    public function __construct(
        private readonly RaceRepositoryInterface       $raceRepository,
        private readonly RaceResultRepositoryInterface $raceResultRepository
    ) {}

    public function __invoke(GetRaceResultQuery $query): array
    {
        $race = $this->raceRepository->findByName($query->raceName);

        if (null === $race) {
            throw new InvalidArgumentException('Race not found');
        }

        /** @var RaceResult[] $raceResults */
        $raceResults = $this->raceResultRepository->findByRace($race->getId());

        return array_map(
            fn($raceResult) => new RaceResultDTO(  // ✅ Création du DTO
                $raceResult->getPosition()->getValue(),
                $raceResult->getDriver()->getFullName(),
                $raceResult->getBestLapTime()->toString(),
                $raceResult->getPoints()->getValue(),
                $raceResult->isPodiumFinish()
            ),
            $raceResults
        );
    }
}
