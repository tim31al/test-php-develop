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
    private const ERROR = 'Not found.';

    public function __construct()
    {
        parent::__construct(['error' => static::ERROR], Response::HTTP_NOT_FOUND);
    }
}
