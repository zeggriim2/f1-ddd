<?php

declare(strict_types=1);

namespace App\Application\CommandHandler\Driver;

use App\Application\Command\Driver\CreateDriverCommand;
use App\Domain\Entity\Driver;
use App\Domain\Repository\DriverRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
final class CreateDriverCommandHandler
{
    public function __construct(
        private DriverRepositoryInterface $driverRepository,
    ) {}

    public function __invoke(CreateDriverCommand $command): void
    {
        // ✅ VALIDATION : Vérification des règles métier
        $existingDriver = $this->driverRepository->findByNumber($command->number);
        if($existingDriver) {
            throw new \InvalidArgumentException(sprintf('Driver with number "%s" already exists.', $command->number));
        }

        // ✅ CRÉATION : Utilisation des Value Objects et Entities
        $driver = new Driver(
            Uuid::v4(),
            $command->firstName,
            $command->lastName,
            $command->nationality,
            $command->number,
        );

        // ✅ PERSISTENCE : Délégué au repository
        $this->driverRepository->save($driver);
    }
}
