<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Controller\Api\v1\Application;

use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/applications/count', name: 'api_applications_count', methods: ['GET'])]
class CountController extends AbstractApiController
{
    #[OA\Response(
        response: 200,
        description: 'Count applications',
        content: new OA\JsonContent(ref: '#/components/schemas/Count')
    )]
    #[Security(name: 'Bearer')]
    #[OA\Tag(name: 'Applications')]
    public function __invoke(): JsonResponse
    {
        $dto = $this->applicationService->count();

        return $this->jsonDto($dto);
    }
}
