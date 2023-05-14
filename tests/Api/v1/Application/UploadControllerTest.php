<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests\Api\v1\Application;

use App\Tests\Api\HelperTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadControllerTest extends WebTestCase
{
    use HelperTrait;

    protected function setUp(): void
    {
        $this->prepareFile();

        parent::setUp();
    }

    public function testUploadFileSuccess(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/v1/applications');

        $data = $this->getData($client);
        $id = $data[0]['id'];

        $uri = sprintf('/api/v1/applications/%d/upload-file', $id);
        $file = new UploadedFile(__DIR__.'/../../data/test.pdf', 'order.pdf');

        $client->request(
            'POST',
            $uri,
            [],
            ['file' => $file],
            ['CONTENT_TYPE' => 'multipart/form-data'],
        );

        $this->assertResponseIsSuccessful();

        $data = $this->getData($client);
        $this->assertArrayHasKey('file', $data);
        $this->assertStringContainsString('order', $data['file']['fileName']);
    }

    public function testUploadReplaceFileSuccess(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/v1/applications');

        $data = $this->getData($client);
        $id = $data[0]['id'];

        $uri = sprintf('/api/v1/applications/%d/upload-file', $id);
        $file = new UploadedFile(__DIR__.'/../../data/test.pdf', 'order.pdf');

        $client->request(
            'POST',
            $uri,
            [],
            ['file' => $file],
            ['CONTENT_TYPE' => 'multipart/form-data'],
        );

        $this->assertResponseIsSuccessful();

        $this->prepareFile();

        $file = new UploadedFile(__DIR__.'/../../data/test.pdf', 'my-test.pdf');
        $client->request(
            'POST',
            $uri,
            [],
            ['file' => $file],
            ['CONTENT_TYPE' => 'multipart/form-data'],
        );

        $data = $this->getData($client);
        $this->assertArrayHasKey('file', $data);
        $this->assertStringContainsString('my-test', $data['file']['fileName']);
    }

    private function prepareFile(): void
    {
        $inPath = __DIR__.'/../../fixtures/test.pdf';
        $outPath = __DIR__.'/../../data/test.pdf';
        if (is_file($inPath)) {
            copy($inPath, $outPath);
        }
    }
}
