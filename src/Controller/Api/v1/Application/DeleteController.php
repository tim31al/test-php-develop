<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Controller\Api\v1\Application;

use Nelmio\ApiDocBundle\Annotation\Security;
use Nelmio\ApiDocBundle\Model\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/applications/{id}', name: 'api_application_delete', methods: ['DELETE'])]
class DeleteController extends AbstractApiController
{
    #[OA\Response(
        response: 200,
        description: 'Returns success',
        content: new OA\JsonContent(ref: '#/components/schemas/Success')
    )]
    #[OA\Response(
        response: 404,
        description: 'Not found',
        content: new OA\JsonContent(ref: '#/components/schemas/NotFound')
    )]
    #[Security(name: 'Bearer')]
    #[OA\Tag(name: 'Applications')]
    public function __invoke(Request $request): JsonResponse
    {
        $dto = $this->applicationService->delete($request);

        return $this->jsonDto($dto);
    }
}
