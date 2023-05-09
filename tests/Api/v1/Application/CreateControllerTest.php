<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests\Api\v1\Application;

use App\Entity\Application;
use App\Repository\ApplicationRepository;
use App\Tests\Api\HelperTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateControllerTest extends WebTestCase
{
    use HelperTrait;

    public function testCreatedGoodByUser1(): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request(
            'POST',
            '/api/v1/applications',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'title' => 'Test Title',
                'text' => 'Test text, text, text',
            ])
        );

        $this->assertResponseStatusCodeSame(201);

        $data = $this->getData($client);

        $this->assertArrayHasKey('id', $data);

        $app = $client->getContainer()->get(ApplicationRepository::class)->find($data['id']);

        $this->assertInstanceOf(Application::class, $app);
        $this->assertSame('Test Title', $app->getTitle());
        $this->assertSame('Test text, text, text', $app->getText());
        $this->assertSame('user1@test.com', $app->getAuthor()->getEmail());
    }

    public function testCreatedGoodByUser2(): void
    {
        $client = $this->createAuthenticatedClient('user2@test.com', 'User2');
        $client->request(
            'POST',
            '/api/v1/applications',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'title' => 'Test',
                'text' => 'Test',
            ])
        );

        $this->assertResponseStatusCodeSame(201);

        $data = $this->getData($client);

        $app = $client->getContainer()->get(ApplicationRepository::class)->find($data['id']);

        $this->assertSame('user2@test.com', $app->getAuthor()->getEmail());
    }

    public function testCreatedEmptyBody(): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request(
            'POST',
            '/api/v1/applications',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertResponseStatusCodeSame(400);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testBadRequest($payload, $expected): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request(
            'POST',
            '/api/v1/applications',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        $this->assertResponseStatusCodeSame($expected);
    }

    public function dataProvider(): array
    {
        return [
            'null body' => [null, 400],
            'bad key' => [['bad' => 'bad'], 400],
            'no key' => [['no key'], 400],
            'only title' => [['title' => 'test'], 400],
            'only text' => [['text' => 'text'], 400],
        ];
    }
}
