<?php

declare(strict_types=1);

namespace App\Controller\F1RaceResult;

use App\Services\RaceResultService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Récupérer les résultats d'une course
 * GET /api/f1/races/{id}/results
 */
#[Route('/races/{id}/results', name: 'api_f1_get_race_results', methods: [Request::METHOD_GET])]
final readonly class ListRaceResultsController
{
    public function __construct(
        private RaceResultService $raceResultService,
    ) {}

    public function __invoke(int $id): JsonResponse
    {
        try {
            $results = $this->raceResultService->getRaceResults($id);

            return new JsonResponse([
                'data' => $results,
                'meta' => [
                    'total' => count($results),
                    'race_id' => $id
                ]
            ]);

        } catch (\InvalidArgumentException $e) {
            return new JsonResponse([
                'error' => 'Race not found',
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
