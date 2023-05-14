<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Controller\Api\v1\Application;

use App\Entity\Application;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/applications/{id}', name: 'api_application_update', methods: ['PUT'])]
class UpdateController extends AbstractApiController
{
    #[OA\RequestBody(content: new Model(type: Application::class, groups: ['update']))]
    #[OA\Response(
        response: 200,
        description: 'Returns success',
        content: new OA\JsonContent(ref: '#/components/schemas/Success')
    )]
    #[OA\Response(
        response: 400,
        description: 'Returns errors',
        content: new OA\JsonContent(ref: '#/components/schemas/BadRequest')
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
        $dto = $this->applicationService->update($request);

        return $this->jsonDto($dto);
    }
}
