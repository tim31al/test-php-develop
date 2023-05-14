<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\DTO;

abstract class AbstractResponseDTO implements ResponseDTOInterface
{
    /**
     * @param array<string, array<string>> $context
     * @param array<string, string>        $headers
     */
    public function __construct(
        private readonly mixed $data,
        private readonly int $code,
        private readonly array $context = [],
        private readonly array $headers = []
    ) {
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return string[][]
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
