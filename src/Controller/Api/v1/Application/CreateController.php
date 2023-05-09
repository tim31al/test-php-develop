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

class CreateController extends AbstractApiController
{
    #[Route('/api/v1/applications', name: 'api_application_create', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $dto = $this->applicationService->create($request);

        return $this->jsonDto($dto);
    }
}
