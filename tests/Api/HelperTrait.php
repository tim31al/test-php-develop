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
    // ['test_ozon@mail.com', 'ozon_0123456789'],
    // ['test_dns@mail.com', 'dns_0123456789']а

    protected function createAuthenticatedClient(string $username = 'test_ozon@mail.com', string $password = 'ozon_0123456789', $client = null)
    {
        if (null === $client) {
            $client = static::createClient();
        }

        $client->request(
            'POST',
            '/api/v1/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => $username,
                'password' => $password,
            ])
        );

        $data = json_decode($client->getResponse()->getContent(), true);

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
}
