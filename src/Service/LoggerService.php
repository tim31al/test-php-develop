<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Service;

use Psr\Log\LoggerInterface;

class LoggerService
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    /**
     * @param array<string, string|int> $errorContext
     */
    public function error(string $prefix, \Throwable $e, array $errorContext = []): void
    {
        $message = sprintf('%s %s', $prefix, $e->getMessage());

        $context = array_merge([
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], $errorContext);

        $this->logger->error($message, $context);
    }
}
