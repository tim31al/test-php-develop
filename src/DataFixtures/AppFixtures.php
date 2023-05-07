<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\DataFixtures;

use App\Entity\Application;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends AbstractFixtures
{
    public const MAIL_DOMAIN = 'company.com';
    private const USERS = ['Ivanov', 'Petrov', 'Sidorov'];
    private const COUNT_APPS = 20;

    public function load(ObjectManager $manager): void
    {
        $users = $this->makeUsers(self::USERS, $manager);

        foreach (range(1, self::COUNT_APPS) as $number) {
            $author = $users[rand(0, 2)];

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
