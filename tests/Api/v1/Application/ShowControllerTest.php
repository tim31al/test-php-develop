<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests\Api\v1\Application;

use App\Tests\Api\HelperTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShowControllerTest extends WebTestCase
{
    use HelperTrait;

    public function testShowByAuthor(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/v1/applications?limit=1');
        list($app) = $this->getData($client);
        $id = $app['id'];

        $client->request('GET', '/api/v1/applications/'.$id);

        $this->assertResponseIsSuccessful();

        $data = $this->getData($client);

        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('title', $data);
        $this->assertArrayHasKey('text', $data);
        $this->assertArrayHasKey('createdAt', $data);

        $this->assertSame('Title 1', $data['title']);
    }

    public function testNotFoundAnotherUser(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/v1/applications?limit=1');
        list($app) = $this->getData($client);
        $id = $app['id'];

        $client = $this->createAuthenticatedClient('user2@test.com', 'User2', $client);

        $client->request('GET', '/api/v1/applications/'.$id);

        $this->assertResponseStatusCodeSame(404);
    }

    public function testShowByAdmin(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/v1/applications?limit=1');
        list($app) = $this->getData($client);
        $id = $app['id'];

        $client = $this->createAuthenticatedClient('admin@test.com', 'Admin', $client);

        $client->request('GET', '/api/v1/applications/'.$id);
        $this->assertResponseIsSuccessful();
    }
}
