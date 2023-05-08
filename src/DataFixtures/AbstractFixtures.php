<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class AbstractFixtures extends Fixture
{
    public const MAIL_DOMAIN = 'test.com';

    protected Generator $faker;

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = Factory::create();
    }

    /**
     * @param string[] $names
     *
     * @return User[]
     */
    protected function makeUsers(array $names, ObjectManager $manager): array
    {
        $users = [];

        foreach ($names as $name) {
            $user = new User();

            $lastname = $firstname = $name;
            $password = $this->passwordHasher->hashPassword($user, $lastname);
            $email = sprintf('%s@%s', mb_strtolower($lastname), static::MAIL_DOMAIN);
            $dateOfBirth = $this->faker->dateTimeBetween('-70 years', '-20 years');

            $user
                ->setEmail($email)
                ->setLastname($lastname)
                ->setFirstname($firstname)
                ->setPassword($password)
                ->setDateOfBirth($dateOfBirth)
                ->setIsVerified(true)
            ;

            $manager->persist($user);

            $users[] = $user;
        }

        return $users;
    }
}
