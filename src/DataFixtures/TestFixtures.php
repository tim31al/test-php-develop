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
    private const COUNT_APPS = 5;
    private const USERNAMES = ['User1', 'User2'];

    public function load(ObjectManager $manager): void
    {
        list($user1, $user2) = $this->makeUsers(self::USERNAMES, $manager);

        foreach (range(1, self::COUNT_APPS) as $number) {
            // 1-3 'user1@mail', other 'user2@mail'
            $author = $number <= 3 ? $user1 : $user2;

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
