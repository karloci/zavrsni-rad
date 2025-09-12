<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\Farm;
use App\Entity\Timezone;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FarmFixtures extends Fixture implements DependentFixtureInterface
{
    public const string FARM_TEST = "FARM_TEST";

    public function load(ObjectManager $manager): void
    {
        $testFarm = new Farm();
        $testFarm->setName("Test");
        $testFarm->setCountry($this->getReference(CountryFixtures::COUNTRY_CROATIA, Country::class));
        $testFarm->setCity($this->getReference(CityFixtures::CITY_ZAGREB, City::class));
        $testFarm->setTimezone($this->getReference(TimezoneFixtures::TIMEZONE_EUROPE_ZAGREB, Timezone::class));
        $testFarm->setEmail("support@zavrsni-rad.com");
        $testFarm->setWebsite("https://www.zavrsni-rad.com");
        $testFarm->setCreatedBy($this->getReference(MainFixtures::USER_ADMIN, User::class));
        $manager->persist($testFarm);

        $manager->flush();
        $this->addReference(self::FARM_TEST, $testFarm);
    }

    public function getDependencies(): array
    {
        return [
            CountryFixtures::class,
            CityFixtures::class,
            TimezoneFixtures::class,
            MainFixtures::class,
        ];
    }
}
