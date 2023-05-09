<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\DTO;

use Symfony\Component\HttpFoundation\Response;

class BadRequestDTO extends AbstractResponseDTO
{
    /**
     * @param array<string|int, string> $errors
     */
    public function __construct(array $errors)
    {
        parent::__construct(['errors' => $errors], Response::HTTP_BAD_REQUEST);
    }
}
