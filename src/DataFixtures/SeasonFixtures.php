<?php

namespace App\DataFixtures;

use App\Entity\Farm;
use App\Entity\Season;
use App\Entity\User;
use DateInterval;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public const string SEASON_TEST = "SEASON_TEST";

    public function load(ObjectManager $manager): void
    {
        $season = new Season();
        $season->setFarm($this->getReference(FarmFixtures::FARM_TEST, Farm::class));
        $season->setName("Test");

        $startDate = new DateTimeImmutable();
        $endDate = clone $startDate;
        $endDate->add(DateInterval::createFromDateString("+2 months"));

        $season->setStartDate($startDate);
        $season->setEndDate($endDate);

        $season->setCreatedBy($this->getReference(MainFixtures::USER_ADMIN, User::class));
        $manager->persist($season);

        $manager->flush();

        $this->addReference(self::SEASON_TEST, $season);
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            FarmFixtures::class
        ];
    }
}
