<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests\Unit;

use App\Service\Application\Validator;
use PHPUnit\Framework\TestCase;

class ApplicationValidatorTest extends TestCase
{
    private Validator $validator;

    protected function setUp(): void
    {
        $this->validator = new Validator();
        parent::setUp();
    }

    public function testInstanceCreated(): void
    {
        $this->assertInstanceOf(Validator::class, $this->validator);
    }

    /**
     * @dataProvider dataProviderStrict
     */
    public function testValidateStrict(array $data, int $expectedCountErrors): void
    {
        $errors = $this->validator->validateApplication($data);
        $this->assertCount($expectedCountErrors, $errors);
    }

    /**
     * @dataProvider dataProviderNotStrict
     */
    public function testValidateNotStrict(array $data, int $expectedCountErrors): void
    {
        $errors = $this->validator->validateApplication($data, false);
        $this->assertCount($expectedCountErrors, $errors);
    }

    public function dataProviderStrict(): array
    {
        return [
            'empty data' => [[], 2],
            'bad key' => [['test' => 'test'], 3],
            'small title' => [['title' => 'o'], 2],
            'good title' => [['title' => 'title 1'], 1],
            'long title' => [['title' => str_repeat('a', 256)], 2],
            'small text' => [['title' => 'title 1', 'text' => 't'], 1],
            'long text' => [['title' => 'title 2', 'text' => str_repeat('a', 65537)], 1],
            'good' => [['title' => 'title 1', 'text' => 'text text'], 0],
        ];
    }

    public function dataProviderNotStrict(): array
    {
        return [
            'empty data' => [[], 1],
            'bad key' => [['test' => 'test'], 1],
            'small title' => [['title' => 'o'], 1],
            'good title' => [['title' => 'title 1'], 0],
            'long title' => [['title' => str_repeat('a', 256)], 1],
            'small text' => [['text' => 't'], 1],
            'long text' => [['text' => str_repeat('a', 65537)], 1],
            'good' => [['title' => 'title 1', 'text' => 'text text'], 0],
        ];
    }
}
