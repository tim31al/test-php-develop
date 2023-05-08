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

    public function error(string $prefix, \Throwable $e): void
    {
        $message = sprintf('%s %s', $prefix, $e->getMessage());
        $this->logger->error($message, [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
    }
}
