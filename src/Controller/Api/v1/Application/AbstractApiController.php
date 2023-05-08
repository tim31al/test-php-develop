<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Controller\Api\v1\Application;

use App\DTO\ResponseDTOInterface;
use App\Service\Application\ApplicationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractApiController extends AbstractController
{
    public function __construct(protected readonly ApplicationService $applicationService)
    {
    }

    protected function jsonDto(ResponseDTOInterface $dto): JsonResponse
    {
        return $this->json($dto->getData(), $dto->getCode(), $dto->getHeaders(), $dto->getContext());
    }
}
