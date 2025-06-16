<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\RaceResultResponseDTO;
use App\DTO\RecordRaceInputDTO;
use App\Entity\Driver;
use App\Entity\Race;
use App\Entity\RaceResult;
use App\Repository\DriverRepository;
use App\Repository\RaceRepository;
use App\Repository\RaceResultRepository;
use App\ValueObject\LapTime;
use App\ValueObject\Position;
use Doctrine\ORM\EntityManagerInterface;
use Webmozart\Assert\Assert;

final class RaceResultService
{
    public function __construct(
        private readonly RaceRepository         $raceRepository,
        private readonly DriverRepository       $driverRepository,
        private readonly RaceResultRepository   $raceResultRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function recordRaceResult(RecordRaceInputDTO $dto):  RaceResultResponseDTO
    {
        $race = $this->raceRepository->find($dto->raceId);
        Assert::notNull($race, 'Race not found');

        $driver = $this->driverRepository->find($dto->driverId);
        Assert::notNull($driver, 'Driver not found');

        try {
            $position = new Position($dto->position);
            $bestLap = LapTime::fromString($dto->bestLapTime);
        } catch (\InvalidArgumentException $e) {
            throw new \DomainException('Invalid race data: ' .$e->getMessage());
        }

        $this->validateRaceResult($race, $driver, $position);

        $raceResult = new RaceResult($race, $driver, $position, $bestLap);

        $this->entityManager->persist($raceResult);
        $this->entityManager->flush();

        return RaceResultResponseDTO::fromEntity($raceResult);
    }


    private function validateRaceResult(Race $race, Driver $driver, Position $position): void
    {
        // Vérifier que la course n'est pas déjà terminée
        if ($race->getDate() > new \DateTime()) {
            throw new \DomainException('Cannot record result for future race');
        }

        // Vérifier qu'il n'y a pas déjà un pilote à cette position
        $existingAtPosition = $this->raceResultRepository->findByRaceAndPosition($race, $position);
        if ($existingAtPosition) {
            throw new \DomainException(
                sprintf('Position %d already taken by %s',
                    $position->getValue(),
                    $existingAtPosition->getDriver()->getName()
                )
            );
        }

        // Vérifier que le pilote n'a pas déjà un résultat
        $existingForDriver = $this->raceResultRepository->findByRaceAndDriver($race, $driver);
        if ($existingForDriver) {
            throw new \DomainException(
                sprintf('Driver %s already has a result for this race', $driver->getName())
            );
        }
    }

    public function getRaceResults(int $raceId): array
    {
        $race = $this->raceRepository->find($raceId);
        Assert::notNull($race, 'Race not found');

        $results = $this->raceResultRepository->findByRaceOrderedByPosition($race);

        return array_map(
            fn(RaceResult $result) => RaceResultResponseDTO::fromEntity($result),
            $results
        );
    }

    /**
     * @return array<string, array<string, int|string>>
     */
    public function getDriverStandings(): array
    {
        $drivers = $this->driverRepository->findAllWithResults();

        // Trier par points (utilise la logique des Value Objects)
        usort($drivers, function(Driver $a, Driver $b) {
            return $b->getTotalPoints()->getValue() <=> $a->getTotalPoints()->getValue();
        });

        return array_map(function(Driver $driver, int $index) {
            return [
                'position' => $index + 1,
                'name' => $driver->getName(),
                'team' => $driver->getTeam()->getName(),
                'points' => $driver->getTotalPoints()->getValue(),
                'wins' => $this->countWins($driver),
                'podiums' => $driver->getPodiumCount()
            ];
        }, $drivers, array_keys($drivers));
    }

    private function countWins(Driver $driver): int
    {
        $wins = 0;
        foreach ($driver->getRaceResults() as $result) {
            if ($result->isWin()) {
                $wins++;
            }
        }
        return $wins;
    }
}
