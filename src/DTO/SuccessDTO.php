<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\DTO;

use Symfony\Component\HttpFoundation\Response;

class SuccessDTO extends AbstractResponseDTO
{
    public function __construct()
    {
        parent::__construct(['success' => true], Response::HTTP_OK);
    }
}
