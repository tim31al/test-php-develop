<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    public function testLoginSuccess(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'user1@test.com',
                'password' => 'User1',
            ])
        );

        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('token', $data);
    }

    public function testLoginUnauthorized(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'user1@test.com',
                'password' => 'bad pass',
            ])
        );

        $this->assertResponseStatusCodeSame(401);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('message', $data);
        $this->assertSame('Invalid credentials.', $data['message']);
    }

    public function testLoginUserNotVerified(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'notverified@test.com',
                'password' => 'NotVerified',
            ])
        );

        $this->assertResponseStatusCodeSame(403);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('error', $data);
        $this->assertSame('Not verified.', $data['error']);
    }
}
