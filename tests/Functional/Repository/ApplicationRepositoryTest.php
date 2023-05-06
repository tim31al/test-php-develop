<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests\Functional\Repository;

use App\Entity\Application;
use App\Entity\User;
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

        $this->assertCount(3, $applications);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
