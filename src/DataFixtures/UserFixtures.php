<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Regular user
        $user = new User();
        $user->setEmail('user@fivermail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setLoyaltyPoints(1000); // Starting points
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, 'password')
        );
        $manager->persist($user);

        // Admin user
        $admin = new User();
        $admin->setEmail('admin@fivermail.com');
        $admin->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $admin->setLoyaltyPoints(5000);
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'adminpassword')
        );
        $manager->persist($admin);

        $manager->flush();
    }
}