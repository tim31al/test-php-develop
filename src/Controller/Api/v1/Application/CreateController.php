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

#[Route('/api/v1/applications', name: 'api_application_create', methods: ['POST'])]
class CreateController extends AbstractApiController
{
    #[OA\RequestBody(content: new Model(type: Application::class, groups: ['create']))]
    #[OA\Response(
        response: 201,
        description: 'Returns application id',
        content: new Model(type: Application::class, groups: ['created'])
    )]
    #[OA\Response(
        response: 400,
        description: 'Returns errors',
        content: new OA\JsonContent(ref: '#/components/schemas/BadRequest')
    )]
    #[Security(name: 'Bearer')]
    #[OA\Tag(name: 'Applications')]
    public function __invoke(Request $request): JsonResponse
    {
        $dto = $this->applicationService->create($request);

        return $this->jsonDto($dto);
    }
}
