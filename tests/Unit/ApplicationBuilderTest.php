<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests\Unit;

use App\Entity\User;
use App\Service\Application\ApplicationBuilder;
use PHPUnit\Framework\TestCase;

class ApplicationBuilderTest extends TestCase
{
    public function testBuild(): void
    {
        $data = ['title' => 'title 1', 'text' => 'text, text, text'];
        $user = (new User())->setEmail('test@mail.com');

        $builder = new ApplicationBuilder();

        $application = $builder->build($data, $user);

        $this->assertSame('title 1', $application->getTitle());
        $this->assertSame('text, text, text', $application->getText());
        $this->assertSame('test@mail.com', $application->getAuthor()->getEmail());
    }
}
