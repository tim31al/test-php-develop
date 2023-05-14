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
    private const ERROR = 'Internal Server Error.';

    public function __construct()
    {
        parent::__construct(['error' => self::ERROR], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
