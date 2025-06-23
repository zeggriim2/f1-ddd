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
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
class EnhancedRegisterRaceResultCommandHandler
{
    public function __construct(
        private DriverRepositoryInterface $driverRepository,
        private RaceRepositoryInterface $raceRepository,
        private RaceResultRepositoryInterface  $raceResultRepository,
        private PodiumFinishSpecification $podiumSpec,
        private PointsScoringSpecification $pointsSpec,
    ) {}

    public function __invoke(RegisterRaceResultCommand $command): void
    {
        $race = $this->raceRepository->findByName($command->raceName);

        if (!$race) {
            throw new \InvalidArgumentException(
                sprintf('Race "%s" not found', $command->raceName)
            );
        }

        $driver = $this->driverRepository->findByNumber($command->driverNumber);
        if (!$driver) {
            throw new \InvalidArgumentException(
                sprintf('Driver "%s" not found', $command->driverNumber)
            );
        }

        $position = new Position($command->position);
        $lapTime = LapTime::fromString($command->bestLapTime);
        $points = Points::fromPosition($position);

        $result = new RaceResult(Uuid::v4(), $race, $driver, $position, $lapTime, $points);

        if ($this->podiumSpec->isSatisfiedBy($result)) {
            error_log(
                sprintf("Podium finish for %s", $driver->getFullName())
            );
        }

        if ($this->pointsSpec->isSatisfiedBy($result)) {
            error_log(
                sprintf("Points scored : %s for %s", $points->getValue(), $driver->getFullName())
            );
        }

        $this->raceResultRepository->save($result);
    }
}
