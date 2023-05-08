<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\DTO;

use App\Entity\Application;
use Symfony\Component\HttpFoundation\Response;

class ApplicationListDTO extends AbstractResponseDTO
{
    /**
     * @param mixed|Application[] $apps
     */
    public function __construct(mixed $apps)
    {
        parent::__construct($apps, Response::HTTP_OK, ['groups' => ['list']]);
    }
}
