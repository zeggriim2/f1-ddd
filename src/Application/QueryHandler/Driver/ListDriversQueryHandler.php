<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\Driver;
use App\Application\DTO\DriverDTO;
use App\Application\Query\Driver\ListDriversQuery;
use App\Domain\Entity\Driver;
use App\Domain\Repository\DriverRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ListDriversQueryHandler
{
    public function __construct(
        private DriverRepositoryInterface $driverRepository
    ) {}


    /**
     * @return DriverDTO[]
     */
    public function __invoke(ListDriversQuery $query): array
    {
        /** @var Driver[] $drivers */
        $drivers = $this->driverRepository->findAll();

        return array_map(
            fn($driver) => new DriverDTO(
                $driver->getFullName(),
                $driver->getNationality(),
                $driver->getNumber(),
            ),
            $drivers
        );
    }
}
