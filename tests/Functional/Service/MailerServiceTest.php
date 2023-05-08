<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests\Functional\Service;

use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Mime\Email;

class MailerServiceTest extends KernelTestCase
{
    private ?MailerService $mailerService;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->mailerService = $container->get(MailerService::class);

        parent::setUp();
    }

    public function testGetAddress(): void
    {
        $address = $this->mailerService->getFromAddress();

        $this->assertSame('mailer@test.test', $address->getAddress());
        $this->assertSame('TestApp Mail Bot', $address->getName());
    }

    public function testSend(): void
    {
        $testEmail = (new Email())
            ->from('from@test.com')
            ->to('to@test.com')
            ->subject('Test test')
            ->html('<h1>Welcome!</h1>');

        $this->mailerService->send($testEmail);

        $this->assertEmailCount(1);

        $email = $this->getMailerMessage();
        $this->assertEmailHtmlBodyContains($email, 'Welcome!');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->mailerService = null;
    }
}
