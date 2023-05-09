<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\EventListener;

use App\Entity\User;
use App\Exception\NotVerifiedException;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

/**
 * Проверка, что пользователь верифицирован.
 */
final class AuthenticationSuccessListener
{
    /**
     * @throws NotVerifiedException
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        // Если не админ и не верифицирован
        if (!\in_array('ROLE_ADMIN', $user->getRoles(), true) && !$user->isVerified()) {
            throw new NotVerifiedException();
        }
    }
}
