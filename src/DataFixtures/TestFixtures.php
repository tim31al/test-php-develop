<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\DataFixtures;

use App\Entity\Application;
use Doctrine\Persistence\ObjectManager;

class TestFixtures extends AbstractFixtures
{
    private const COUNT_APPS_USER1 = 10;
    private const COUNT_APPS_USER2 = 5;
    private const USERNAMES = ['User1', 'User2', 'NotVerified', 'Admin'];

    public function load(ObjectManager $manager): void
    {
        list($user1, $user2, $notVerified) = $this->makeUsers(self::USERNAMES, $manager);

        $notVerified->setIsVerified(false);

        foreach (range(1, self::COUNT_APPS_USER1) as $number) {
            $author = $user1;

            $application = new Application();
            $application
                ->setTitle('Title '.$number)
                ->setText($this->faker->text())
                ->setAuthor($author);

            $manager->persist($application);
        }

        foreach (range(1, self::COUNT_APPS_USER2) as $number) {
            $author = $user2;

            $application = new Application();
            $application
                ->setTitle('Title '.$number)
                ->setText($this->faker->text())
                ->setAuthor($author);

            $manager->persist($application);
        }

        $manager->flush();
    }
}
