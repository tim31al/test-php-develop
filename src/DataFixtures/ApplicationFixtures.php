<?php

namespace App\DataFixtures;

use App\Entity\Application;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ApplicationFixtures extends Fixture
{
    const COUNT_APPS = 5;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        foreach (range(1, self::COUNT_APPS) as $number) {
            $application = new Application();
            $application
                ->setTitle('Title ' . $number)
                ->setText($faker->text());

            $manager->persist($application);
        }

        $manager->flush();
    }
}
