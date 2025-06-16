<?php

declare(strict_types=1);

namespace App\Controller\F1RaceResult;

use App\DTO\RecordRaceInputDTO;
use App\Services\RaceResultService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Enregistrer un résultat de course
 * POST /api/f1/race-results
 */
#[Route('/race-results', name: 'api_f1_record_result', methods: [Request::METHOD_POST])]
final readonly class RecordRaceResultController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface  $validator,
        private RaceResultService   $raceResultService,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            // 1. Désérialisation du JSON en DTO
            $dto = $this->serializer->deserialize(
                $request->getContent(),
                RecordRaceInputDTO::class,
                'json'
            );

            // 2. Validation avec Symfony Validator
            $errors = $this->validator->validate($dto);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }
                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            // 3. Traitement métier via le service (qui utilise les Value Objects)
            $result = $this->raceResultService->recordRaceResult($dto);

            // 4. Réponse avec le DTO de sortie
            return new JsonResponse([
                'message' => 'Race result recorded successfully',
                'data' => $result
            ], Response::HTTP_CREATED);

        } catch (\DomainException $e) {
            // Erreurs métier (validations Value Objects, règles business)
            return new JsonResponse([
                'error' => 'Business rule violation',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        } catch (\InvalidArgumentException $e) {
            // Erreurs de données (entités non trouvées, etc.)
            return new JsonResponse([
                'error' => 'Invalid data',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            // Erreurs techniques
            return new JsonResponse([
                'error' => 'Internal server error',
                'message' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
