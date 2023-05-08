<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\DTO;

interface ResponseDTOInterface
{
    public function getData(): mixed;

    public function getCode(): int;

    /**
     * @return string[]
     */
    public function getHeaders(): array;

    /**
     * @return string[][]
     */
    public function getContext(): array;
}
