<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests\Api\v1\Application;

use App\Tests\Api\HelperTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteControllerTest extends WebTestCase
{
    use HelperTrait;

    public function testDeleteSuccess(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/v1/applications');

        $data = $this->getData($client);
        $id = $data[0]['id'];

        $client->request('DELETE', '/api/v1/applications/'.$id);

        $this->assertResponseIsSuccessful();

        $data = $this->getData($client);
        $this->assertArrayHasKey('success', $data);
        $this->assertTrue($data['success']);
    }

    public function testDeleteSuccessCheckCount(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/v1/applications');

        $data = $this->getData($client);
        $id = $data[0]['id'];
        $currentCount = \count($data);

        $client->request('DELETE', '/api/v1/applications/'.$id);

        $client->request('GET', '/api/v1/applications');

        $data = $this->getData($client);
        $this->assertCount($currentCount - 1, $data);
    }

    public function testDeleteNotFound(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/v1/applications');

        $data = $this->getData($client);
        $id = $data[0]['id'];

        $client = $this->createAuthenticatedClient('user2@test.com', 'User2', $client);

        $client->request('DELETE', '/api/v1/applications/'.$id);
        $this->assertResponseStatusCodeSame(404);
    }

    public function testDeleteByAdmin(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/v1/applications');

        $data = $this->getData($client);
        $id = $data[0]['id'];

        $client = $this->createAuthenticatedClient('admin@test.com', 'Admin', $client);

        $client->request('DELETE', '/api/v1/applications/'.$id);
        $this->assertResponseStatusCodeSame(200);
    }
}
