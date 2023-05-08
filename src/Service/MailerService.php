<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Service;

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
    
    public function getFromAddress(): Address
    {
        return new Address($this->fromEmail, self::ADDRESS_NAME);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(RawMessage $message): void
    {
        $this->mailer->send($message);
    }
}
