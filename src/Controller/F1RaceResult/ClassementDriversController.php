<?php

declare(strict_types=1);

namespace App\Controller\F1RaceResult;

use App\Services\RaceResultService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Classement des pilotes
 * GET /api/f1/standings/drivers
 */
#[Route('/standings/drivers', name: 'api_f1_driver_standings', methods: [Request::METHOD_GET])]
final readonly class ClassementDriversController
{
    public function __construct(
        private RaceResultService $raceResultService,
    ) {}

    public function __invoke(): JsonResponse
    {
        $standings = $this->raceResultService->getDriverStandings();

        return new JsonResponse([
            'data' => $standings,
            'meta' => [
                'total_drivers' => count($standings),
                'generated_at' => (new \DateTime())->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
