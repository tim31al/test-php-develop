<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\DTO;

use Symfony\Component\HttpFoundation\Response;

class ApplicationsCountDTO extends AbstractResponseDTO
{
    public function __construct(mixed $count)
    {
        $data = ['count' => $count];
        parent::__construct($data, Response::HTTP_OK);
    }
}
