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

class ListController extends AbstractApiController
{
    #[Route('/api/v1/applications', name: 'api_applications_list', methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $dto = $this->applicationService->list($request);

        return $this->jsonDto($dto);
    }
}
