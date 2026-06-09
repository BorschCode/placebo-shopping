<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use App\Entity\User;
use App\Enum\Theme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    public const USERS = 21;

    public function __construct(private readonly UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('uk_UA');
        $themes = Theme::cases();

        for ($i = 0; $i < self::USERS; $i++) {
            $user = new User();
            $email = $i === 0 ? 'admin@placebo.local' : 'user' . $i . '@example.com';
            $user->setEmail($email);
            $user->setPassword($this->hasher->hashPassword($user, 'password'));
            $user->setIsVerified(true);
            $user->setTheme($themes[$i % count($themes)]);
            if ($i === 0) {
                $user->setRoles(['ROLE_ADMIN']);
            }

            $profile = new Profile();
            $profile->setDisplayName($faker->firstName() . ' ' . $faker->lastName());
            $profile->setBio($faker->sentences(2, true));
            $profile->setPhone($faker->phoneNumber());
            $profile->setLocation($faker->city());
            $profile->setUser($user);

            $manager->persist($user);
            $manager->persist($profile);

            $this->addReference('user_' . $i, $user);
        }

        $manager->flush();
    }
}
