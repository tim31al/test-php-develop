<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\RawMessage;

class MailerService
{
    public const ADDRESS_NAME = 'TestApp Mail Bot';

    public function __construct(private readonly MailerInterface $mailer, private readonly string $fromEmail)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(RawMessage $message): void
    {
        $this->mailer->send($message);
    }

    public function getConfirmationEmail(User $user): TemplatedEmail
    {
        $address = new Address($this->fromEmail, self::ADDRESS_NAME);
        $confirmationEmail = new TemplatedEmail();

        $email = $user->getEmail();
        if (null === $email) {
            throw new \InvalidArgumentException('User email empty.');
        }

        $confirmationEmail
            ->from($address)
            ->to($email)
            ->subject('Please Confirm your Email')
            ->htmlTemplate('registration/confirmation_email.html.twig');

        return $confirmationEmail;
    }
}
