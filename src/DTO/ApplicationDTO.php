<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\DTO;

use App\Entity\Application;
use Symfony\Component\HttpFoundation\Response;

class ApplicationDTO extends AbstractResponseDTO
{
    public function __construct(Application $application)
    {
        parent::__construct($application, Response::HTTP_OK, ['groups' => ['show']]);
    }
}
