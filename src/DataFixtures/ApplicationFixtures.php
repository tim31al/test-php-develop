<?php

namespace App\DataFixtures;

use App\Entity\Application;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ApplicationFixtures extends Fixture
{
    const COUNT_APPS = 5;
    const MAIL_DOMAIN = 'test.com';

    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        list($user1, $user2) = $this->makeUsers($manager);

        foreach (range(1, self::COUNT_APPS) as $number) {
            // 1-3 'user1@mail., other $user2
            $author = $number <= 3 ? $user1 : $user2;

            $application = new Application();
            $application
                ->setTitle('Title ' . $number)
                ->setText($faker->text())
                ->setAuthor($author)
                ;

            $manager->persist($application);
        }

        $manager->flush();
    }

    /**
     * @return User[]
     */
    private function makeUsers(ObjectManager $manager): array
    {
        $users = [];

        foreach (range(1, 2) as $number) {

            $user = new User();

            $lastname = $firstname = 'User' . $number;
            $password = $this->passwordHasher->hashPassword($user, $lastname);
            $email = sprintf('%s@%s', mb_strtolower($lastname), self::MAIL_DOMAIN);

            $user
                ->setEmail($email)
                ->setLastname($lastname)
                ->setFirstname($firstname)
                ->setPassword($password);

            $manager->persist($user);

            $users[] = $user;
        }

        return $users;
    }
}
