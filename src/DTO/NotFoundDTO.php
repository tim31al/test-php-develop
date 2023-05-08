<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\DTO;

use Symfony\Component\HttpFoundation\Response;

class NotFoundDTO extends AbstractResponseDTO
{
    public function __construct()
    {
        parent::__construct(['error' => 'Not found.'], Response::HTTP_NOT_FOUND);
    }
}
