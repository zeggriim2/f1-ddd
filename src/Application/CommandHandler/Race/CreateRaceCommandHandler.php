<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Race;

use App\Application\Command\Race\CreateRaceCommand;
use App\Domain\Entity\Race;
use App\Domain\Repository\RaceRepositoryInterface;
use DateTimeImmutable;
use InvalidArgumentException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
final class CreateRaceCommandHandler
{
    public function __construct(
        private RaceRepositoryInterface $raceRepository,
    ) {}

    public function __invoke(CreateRaceCommand $command): void
    {
        $existingRace = $this->raceRepository->findByName($command->name);
        if ($existingRace) {
            throw new InvalidArgumentException('Race already exists!');
        }

        $race = new Race(
            Uuid::v4(),
            $command->name,
            $command->circuit,
            new DateTimeImmutable($command->date),
        );

        $this->raceRepository->save($race);
    }
}
