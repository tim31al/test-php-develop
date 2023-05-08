<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\DTO;

use Symfony\Component\HttpFoundation\Response;

class InternalServerErrorDTO extends AbstractResponseDTO
{
    public function __construct()
    {
        parent::__construct(['error' => 'Internal Server Error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
