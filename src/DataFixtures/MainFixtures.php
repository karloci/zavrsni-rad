<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MainFixtures extends Fixture
{
    public const string USER_ADMIN = "USER_ADMIN";
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $adminUser = new User();
        $adminUser->setFirstName("Admin");
        $adminUser->setLastName("Test");
        $adminUser->setEmail("admin@zavrsni-rad.com");
        $adminUser->setEmailVerifiedAt(new DateTimeImmutable());
        $adminUser->setRoles(["ROLE_ADMIN"]);
        $adminUser->setPassword($this->passwordHasher->hashPassword($adminUser, "admin"));
        $manager->persist($adminUser);

        $manager->flush();
        $this->addReference(self::USER_ADMIN, $adminUser);
    }
}
