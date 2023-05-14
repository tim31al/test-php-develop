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

#[Route('/api/v1/applications', name: 'api_applications_list', methods: ['GET'])]
class ListController extends AbstractApiController
{
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Application::class, groups: ['list']))
        )
    )]
    #[OA\Parameter(
        name: 'limit',
        description: 'Limit',
        in: 'query',
        schema: new OA\Schema(type: 'int'),
        example: '10'
    )]
    #[OA\Parameter(
        name: 'offset',
        description: 'Offset',
        in: 'query',
        schema: new OA\Schema(type: 'int'),
        example: '5'
    )]
    #[OA\Parameter(
        name: 'sort',
        description: 'Sort order',
        in: 'query',
        schema: new OA\Schema(type: 'string', default: 'asc'),
        example: 'desc'
    )]
    #[Security(name: 'Bearer')]
    #[OA\Tag(name: 'Applications')]
    public function __invoke(Request $request): JsonResponse
    {
        $dto = $this->applicationService->list($request);

        return $this->jsonDto($dto);
    }
}
