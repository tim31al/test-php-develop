<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests\Api\v1\Application;

use App\Tests\Api\HelperTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UpdateControllerTest extends WebTestCase
{
    use HelperTrait;

    public function testUpdateSuccess(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/v1/applications');

        $data = $this->getData($client);
        $id = $data[0]['id'];

        $client->request(
            'PUT',
            '/api/v1/applications/'.$id,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'title' => 'Test',
                'text' => 'Test',
            ])
        );

        $this->assertResponseIsSuccessful();

        $data = $this->getData($client);
        $this->assertArrayHasKey('success', $data);
        $this->assertTrue($data['success']);
    }

    /**
     * @dataProvider badDataProvider
     */
    public function testUpdateBadRequest(mixed $data): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/v1/applications');

        $data = $this->getData($client);
        $id = $data[0]['id'];

        $client->request(
            'PUT',
            '/api/v1/applications/'.$id,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function badDataProvider(): array
    {
        return [
            'null data' => [[null]],
            'empty data' => [[]],
            'bad key' => ['key' => '123'],
            'bad 2 keys' => ['key1' => 'one', 'key2' => 'two'],
        ];
    }

    public function testUpdateSuccessCheckValues(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/v1/applications');

        $data = $this->getData($client);
        $id = $data[0]['id'];

        $url = '/api/v1/applications/'.$id;

        $client->request(
            'PUT',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'title' => 'Test',
                'text' => 'Test',
            ])
        );

        $client->request('GET', $url);
        $data = $this->getData($client);

        $this->assertArrayHasKey('updatedAt', $data);
        $this->assertSame('Test', $data['title']);
        $this->assertSame('Test', $data['text']);
    }

    public function testUpdateNotFound(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/v1/applications');

        $data = $this->getData($client);
        $id = $data[0]['id'];

        $client = $this->createAuthenticatedClient('user2@test.com', 'User2', $client);

        $client->request('PUT', '/api/v1/applications/'.$id);
        $this->assertResponseStatusCodeSame(404);
    }

    public function testUpdateByAdmin(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/v1/applications');

        $data = $this->getData($client);
        $id = $data[0]['id'];

        $client = $this->createAuthenticatedClient('admin@test.com', 'Admin', $client);

        $client->request(
            'PUT',
            '/api/v1/applications/'.$id,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'title' => 'Admin title',
            ])
        );
        $this->assertResponseStatusCodeSame(200);
    }
}
