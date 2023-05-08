<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests\Api\v1\Application;

use App\Tests\Api\HelperTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CountControllerTest extends WebTestCase
{
    use HelperTrait;

    public function testCountByAuthor1(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/v1/applications/count');

        $this->assertResponseIsSuccessful();

        $data = $this->getData($client);

        $this->assertSame(10, $data['count']);
    }

    public function testCountByAuthor2(): void
    {
        $client = $this->createAuthenticatedClient('user2@test.com', 'User2');

        $client->request('GET', '/api/v1/applications/count');

        $this->assertResponseIsSuccessful();

        $data = $this->getData($client);

        $this->assertSame(5, $data['count']);
    }

    public function testCountByAdmin(): void
    {
        $client = $this->createAuthenticatedClient('admin@test.com', 'Admin');

        $client->request('GET', '/api/v1/applications/count');

        $this->assertResponseIsSuccessful();

        $data = $this->getData($client);

        $this->assertSame(15, $data['count']);
    }
}
