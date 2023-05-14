<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\DataFixtures;

use App\Entity\Application;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends AbstractFixtures
{
    public const MAIL_DOMAIN = 'company.com';
    private const USERS = ['Ivanov', 'Petrov', 'Sidorov'];
    private const COUNT_APPS = [120, 50, 20];

    public function load(ObjectManager $manager): void
    {
        list($ivanov, $petrov, $sidorov) = $this->makeUsers(self::USERS, $manager);

        foreach (self::COUNT_APPS as $idx => $count) {
            $author = match ($idx) {
                0 => $ivanov,
                1 => $petrov,
                default => $sidorov,
            };

            foreach (range(1, $count) as $number) {
                $application = $this->createApplication($number, $author);
                $manager->persist($application);
            }
        }

        $manager->flush();
    }

    private function createApplication(int $number, User $author): Application
    {
        $application = new Application();
        $application
            ->setTitle(sprintf('Title %d', $number))
            ->setText($this->faker->text())
            ->setAuthor($author);

        return $application;
    }
}
