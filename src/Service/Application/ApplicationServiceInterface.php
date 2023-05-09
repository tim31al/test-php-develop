<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Service\Application;

use App\DTO\ResponseDTOInterface;
use Symfony\Component\HttpFoundation\Request;

interface ApplicationServiceInterface
{
    public function count(): ResponseDTOInterface;

    public function list(Request $request): ResponseDTOInterface;

    public function show(Request $request): ResponseDTOInterface;

    public function create(Request $request): ResponseDTOInterface;

    public function delete(Request $request): ResponseDTOInterface;

    public function update(Request $request): ResponseDTOInterface;
}
