<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests\Api\v1\Application;

use App\Tests\Api\HelperTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListControllerTest extends WebTestCase
{
    use HelperTrait;

    public function testListAll(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/v1/applications');

        $this->assertResponseIsSuccessful();

        $data = $this->getData($client);

        $this->assertCount(10, $data);
    }

    public function testLimitOffset(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/v1/applications?limit=2&offset=2');

        $this->assertResponseIsSuccessful();

        $data = $this->getData($client);

        $this->assertCount(2, $data);

        $number = 3;
        foreach ($data as $application) {
            $this->assertSame('Title '.$number, $application['title']);
            ++$number;
        }
    }

    public function testLimitOffsetWithDescSort(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/v1/applications?limit=5&offset=5&sort=desc');

        $this->assertResponseIsSuccessful();

        $data = $this->getData($client);

        $this->assertCount(5, $data);

        $number = 5;
        foreach ($data as $application) {
            $this->assertSame('Title '.$number, $application['title']);
            --$number;
        }
    }

    public function testListAllForAdmin(): void
    {
        $client = $this->createAuthenticatedClient('admin@test.com', 'Admin');

        $client->request('GET', '/api/v1/applications');

        $this->assertResponseIsSuccessful();

        $data = $this->getData($client);

        $this->assertCount(15, $data);
    }
}
