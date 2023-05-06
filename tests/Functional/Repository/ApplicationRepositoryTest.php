<?php

namespace App\Tests\Functional\Repository;

use App\Entity\Application;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ApplicationRepositoryTest extends KernelTestCase
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        parent::setUp();
    }

    /**
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
     * @throws NotSupported
     */
    public function testCountAll(): void
    {
        $applications = $this->entityManager
            ->getRepository(Application::class)
            ->findAll();

        $this->assertCount(5, $applications);

    }
}
