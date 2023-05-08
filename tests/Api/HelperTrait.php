<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests\Api;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait HelperTrait
{
    protected function createAuthenticatedClient(string $username = 'user1@test.com', string $password = 'User1', $client = null): KernelBrowser
    {
        if (null === $client) {
            $client = static::createClient();
        }

        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => $username,
                'password' => $password,
            ])
        );

        $data = $this->getData($client);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    protected static function createAuthenticatedClientWithEncoder(array $claims): KernelBrowser
    {
        $client = self::createClient();
        $encoder = $client->getContainer()->get(JWTEncoderInterface::class);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $encoder->encode($claims)));

        return $client;
    }

    protected function getData(KernelBrowser $client): array
    {
        return json_decode($client->getResponse()->getContent(), true);
    }
}
