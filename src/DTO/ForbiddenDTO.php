<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\DTO;

use Symfony\Component\HttpFoundation\Response;

class ForbiddenDTO extends AbstractResponseDTO
{
    public function __construct()
    {
        parent::__construct(['error' => 'Forbidden.'], Response::HTTP_FORBIDDEN);
    }
}
