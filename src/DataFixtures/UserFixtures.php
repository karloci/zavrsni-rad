<?php

namespace App\DataFixtures;

use App\Entity\Farm;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $testUser = new User();
        $testUser->setFirstName("Test");
        $testUser->setLastName("Test");
        $testUser->setEmail("test@zavrsni-rad.com");
        $testUser->setRoles(["ROLE_OWNER"]);
        $testUser->setPassword($this->passwordHasher->hashPassword($testUser, "test"));
        $testUser->setFarm($this->getReference(FarmFixtures::FARM_TEST, Farm::class));
        $manager->persist($testUser);

        $dummyUser = new User();
        $dummyUser->setFirstName("Dummy");
        $dummyUser->setLastName("Test");
        $dummyUser->setEmail("dummy@zavrsni-rad.com");
        $dummyUser->setPassword($this->passwordHasher->hashPassword($dummyUser, "dummy"));
        $manager->persist($dummyUser);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            FarmFixtures::class
        ];
    }
}
