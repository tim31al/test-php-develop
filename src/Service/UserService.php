<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Service;

use App\Entity\User;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private const VERIFY_EMAIL_ROUTE = 'app_verify_email';
    public const SUCCESS_REGISTER_MESSAGE = 'A confirmation email has been sent to you.';
    public const ERROR_REGISTER_MESSAGE = 'Failed to register user.';
    public const SUCCESS_VERIFY_MESSAGE = 'Your email address has been verified.';

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $entityManager,
        private readonly EmailVerifier $emailVerifier,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Регистрация пользователя.
     */
    public function registerUser(User $user, FormInterface $form): bool
    {
        try {
            $plainPassport = $form->get('plainPassword')->getData();
            if (!\is_string($plainPassport)) {
                throw new \InvalidArgumentException('PlainPassword bad value');
            }

            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $plainPassport
                )
            );

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            // отправить письмо подтверждения
            $this->emailVerifier->sendEmailConfirmation(self::VERIFY_EMAIL_ROUTE, $user);

            return true;
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return false;
        }
    }
}
