<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Controller\Api\v1\Application;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CountController extends AbstractApiController
{
    #[Route('/api/v1/applications/count', name: 'api_applications_count', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $dto = $this->applicationService->count();

        return $this->jsonDto($dto);
    }
}
