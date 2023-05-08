<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\EventListener;

use App\Exception\NotVerifiedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Для api вернуть JsonResponse.
 */
class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof NotVerifiedException) {
            $response = new JsonResponse(['error' => $exception->getMessage()], $exception->getCode());
            $event->setResponse($response);
        }
    }
}
