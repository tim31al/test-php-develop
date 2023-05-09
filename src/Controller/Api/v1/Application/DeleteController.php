<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Controller\Api\v1\Application;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController extends AbstractApiController
{
    #[Route('/api/v1/applications/{id}', name: 'api_application_delete', methods: ['DELETE'])]
    public function __invoke(Request $request): JsonResponse
    {
        $dto = $this->applicationService->delete($request);

        return $this->jsonDto($dto);
    }
}
