<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\DTO;

use App\Entity\Application;
use Symfony\Component\HttpFoundation\Response;

class ApplicationCreatedDTO extends AbstractResponseDTO
{
    public function __construct(Application $application)
    {
        parent::__construct($application, Response::HTTP_CREATED, ['groups' => ['created']]);
    }
}
