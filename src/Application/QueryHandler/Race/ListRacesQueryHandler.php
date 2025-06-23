<?php

declare(strict_types=1);

namespace App\Application\QueryHandler\Race;

use App\Application\DTO\RaceDTO;
use App\Application\Query\Race\ListRacesQuery;
use App\Domain\Entity\Race;
use App\Domain\Repository\RaceRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ListRacesQueryHandler
{
    public function __construct(
        private RaceRepositoryInterface $raceRepository,
    ) {}

    /**
     * @return RaceDTO[]
     */
    public function __invoke(ListRacesQuery $query): array
    {
        /** @var Race[] $races */
        $races = $this->raceRepository->findAll();

        return array_map(
            fn($race) => new RaceDTO(
                $race->getName(),
                $race->getCircuit(),
                $race->getDate()->format('Y-m-d'),
            ),
            $races
        );
    }
}
