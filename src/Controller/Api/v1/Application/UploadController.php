<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Controller\Api\v1\Application;

use App\Entity\Application;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/applications/{id}/upload-file', name: 'api_application_upload_file', methods: ['POST'])]
class UploadController extends AbstractApiController
{
    #[OA\RequestBody(
        description: 'Returns application',
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(properties: [
                new OA\Property(property: 'file', type: 'string', format: 'binary'),
            ], type: 'object')
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns application',
        content: new Model(type: Application::class, groups: ['show'])
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
    public function __invoke(Request $request, Application $application, EntityManagerInterface $em): JsonResponse
    {
        $dto = $this->applicationService->upload($request);

        return $this->jsonDto($dto);
    }
}
