<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests\Functional\Repository;

use App\Entity\Application;
use App\Entity\User;
use App\Repository\ApplicationRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ApplicationRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        parent::setUp();
    }

    /**
     * Поиск по названию.
     *
     * @throws NotSupported
     */
    public function testSearchByTitle(): void
    {
        $application = $this->entityManager
            ->getRepository(Application::class)
            ->findOneBy(['title' => 'Title 1']);

        $this->assertInstanceOf(Application::class, $application);
    }

    /**
     * Поиск по автору.
     *
     * @throws NotSupported
     */
    public function testFindByAuthor(): void
    {
        $author = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => 'user1@test.com']);

        $applications = $this->entityManager
            ->getRepository(Application::class)
            ->findBy(['author' => $author]);

        $this->assertCount(10, $applications);
    }

    /**
     * Поиск по автору с пагинацией.
     *
     * @dataProvider paginationUser1Provider
     *
     * @throws NotSupported
     */
    public function testFindByAuthorWithPagination(int $limit, int $offset, $expected): void
    {
        $author = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => 'user1@test.com']);

        /** @var ApplicationRepository $repository */
        $repository = $this->entityManager->getRepository(Application::class);

        $applications = $repository->findByUser($limit, $offset, $author);

        $this->assertCount($expected, $applications);
    }

    /**
     * Поиск по автору с пагинацией.
     *
     * @dataProvider paginationAdminProvider
     *
     * @throws NotSupported
     */
    public function testFindByAuthorWithPaginationWithoutUser(int $limit, int $offset, $expected): void
    {
        /** @var ApplicationRepository $repository */
        $repository = $this->entityManager->getRepository(Application::class);

        $applications = $repository->findByUser($limit, $offset);

        $this->assertCount($expected, $applications);
    }

    public function paginationUser1Provider(): array
    {
        return [
            [2, 0, 2],
            [10, 0, 10],
            [5, 5, 5],
            [10, 10, 0],
        ];
    }

    public function paginationAdminProvider(): array
    {
        return [
            [2, 0, 2],
            [100, 15, 0],
            [15, 0, 15],
            [10, 10, 5],
        ];
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
