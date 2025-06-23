<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\RaceResult;

use App\Application\Command\RaceResult\RegisterRaceResultCommand;
use App\Domain\Entity\RaceResult;
use App\Domain\Entity\ValueObject\LapTime;
use App\Domain\Entity\ValueObject\Points;
use App\Domain\Entity\ValueObject\Position;
use App\Domain\Repository\DriverRepositoryInterface;
use App\Domain\Repository\RaceRepositoryInterface;
use App\Domain\Repository\RaceResultRepositoryInterface;
use App\Domain\Specification\PodiumFinishSpecification;
use App\Domain\Specification\PointsScoringSpecification;
use InvalidArgumentException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
final class RegisterRaceResultCommandHandler
{
    public function __construct(
        private DriverRepositoryInterface $driverRepository,
        private RaceRepositoryInterface $raceRepository,
        private RaceResultRepositoryInterface $raceResultRepository,
    ) {}

    public function __invoke(RegisterRaceResultCommand $command): void
    {
        // ✅ RÉCUPÉRATION : Via les repositories
        $race = $this->raceRepository->findByName($command->raceName);

        if(!$race) {
            throw new InvalidArgumentException(sprintf('Race "%s" not found', $command->raceName));
        }

        $driver = $this->driverRepository->findByNumber($command->driverNumber);
        if (!$driver) {
            throw new InvalidArgumentException(sprintf('Driver "%s" not found', $command->driverNumber));
        }

        $existingResult = $this->raceResultRepository->findRaceAndDriver($race->getId(), $driver->getId());

        if ($existingResult) {
            throw new 
        }

        $position = new Position($command->position);
        $lapTime = LapTime::fromString($command->bestLapTime);
        $points = Points::fromPosition($position);

        $result = new RaceResult(
            Uuid::v4(),
            $race,
            $driver,
            $position,
            $lapTime,
            $points
        );

        $this->raceResultRepository->save($result);
    }
}
