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
    private const ERROR = 'Forbidden.';

    public function __construct()
    {
        parent::__construct(['error' => static::ERROR], Response::HTTP_FORBIDDEN);
    }
}
