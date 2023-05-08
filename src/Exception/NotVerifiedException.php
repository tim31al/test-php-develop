<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class NotVerifiedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Not verified.', Response::HTTP_FORBIDDEN);
    }
}
