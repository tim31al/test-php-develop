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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/applications/{id}', name: 'api_application_delete', methods: ['DELETE'])]
class DeleteController extends AbstractApiController
{
    #[OA\Response(
        response: 200,
        description: 'Returns application',
        content: new OA\JsonContent(type: 'object', example: ['success' => true])
    )]
    #[OA\Response(
        response: 404,
        description: 'Not found',
        content: new OA\JsonContent(type: 'object', example: ['error' => 'Not found.'])
    )]
    #[Security(name: 'Bearer')]
    #[OA\Tag(name: 'Applications')]
    public function __invoke(Request $request): JsonResponse
    {
        $dto = $this->applicationService->delete($request);

        return $this->jsonDto($dto);
    }
}
