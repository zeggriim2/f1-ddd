<?php

declare(strict_types=1);

namespace App\Controller\F1RaceResult;

use App\ValueObject\LapTime;
use App\ValueObject\Position;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Validation en temps réel des données de course
 * POST /api/f1/validate-result
 */
#[Route('/validate-result', name: 'api_f1_validate_result', methods: [Request::METHOD_POST])]
final class ValidateRaceResult
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            // Validation avec les Value Objects sans persistance
            $errors = [];

            // Test Position
            try {
                new Position($data['position'] ?? 0);
            } catch (InvalidArgumentException $e) {
                $errors['position'] = $e->getMessage();
            }

            // Test LapTime
            try {
                LapTime::fromString($data['bestLapTime'] ?? '');
            } catch (InvalidArgumentException $e) {
                $errors['bestLapTime'] = $e->getMessage();
            }

            if (empty($errors)) {
                $data = ['valid' => true, 'message' => 'Data is valid'];
                $status = Response::HTTP_OK;
            } else {
                $data = ['valid' => false, 'errors' => $errors];
                $status = Response::HTTP_BAD_REQUEST;
            }

            return new JsonResponse($data, $status);

        } catch (\Exception $e) {
            return new JsonResponse(['valid' => false, 'error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }
    }
}
