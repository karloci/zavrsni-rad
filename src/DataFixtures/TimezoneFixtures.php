<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\Timezone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TimezoneFixtures extends Fixture implements DependentFixtureInterface
{
    public const string TIMEZONE_EUROPE_ZAGREB = "TIMEZONE_EUROPE_ZAGREB";

    public function load(ObjectManager $manager): void
    {
        $timezonesData = [
            ["name" => "Europe/Zagreb", "code" => "Europe/Zagreb", "country" => CountryFixtures::COUNTRY_CROATIA],
            ["name" => "Europe/Belgrade", "code" => "Europe/Belgrade", "country" => CountryFixtures::COUNTRY_SERBIA],
            ["name" => "Europe/Ljubljana", "code" => "Europe/Ljubljana", "country" => CountryFixtures::COUNTRY_SLOVENIA],
            ["name" => "Europe/Berlin", "code" => "Europe/Berlin", "country" => CountryFixtures::COUNTRY_GERMANY],
            ["name" => "Europe/Paris", "code" => "Europe/Paris", "country" => CountryFixtures::COUNTRY_FRANCE],
            ["name" => "Europe/Amsterdam", "code" => "Europe/Amsterdam", "country" => CountryFixtures::COUNTRY_NETHERLANDS],
            ["name" => "Europe/Rome", "code" => "Europe/Rome", "country" => CountryFixtures::COUNTRY_ITALY],
            ["name" => "Europe/Madrid", "code" => "Europe/Madrid", "country" => CountryFixtures::COUNTRY_SPAIN],
            ["name" => "Europe/Warsaw", "code" => "Europe/Warsaw", "country" => CountryFixtures::COUNTRY_POLAND],
            ["name" => "Europe/Vienna", "code" => "Europe/Vienna", "country" => CountryFixtures::COUNTRY_AUSTRIA],
            ["name" => "Europe/Budapest", "code" => "Europe/Budapest", "country" => CountryFixtures::COUNTRY_HUNGARY],
            ["name" => "Europe/Bucharest", "code" => "Europe/Bucharest", "country" => CountryFixtures::COUNTRY_ROMANIA],
            ["name" => "Europe/Lisbon", "code" => "Europe/Lisbon", "country" => CountryFixtures::COUNTRY_PORTUGAL],
            ["name" => "Europe/Athens", "code" => "Europe/Athens", "country" => CountryFixtures::COUNTRY_GREECE],
            ["name" => "Europe/Sofia", "code" => "Europe/Sofia", "country" => CountryFixtures::COUNTRY_BULGARIA],
            ["name" => "Europe/Prague", "code" => "Europe/Prague", "country" => CountryFixtures::COUNTRY_CZECH_REPUBLIC],
            ["name" => "Europe/Brussels", "code" => "Europe/Brussels", "country" => CountryFixtures::COUNTRY_BELGIUM],
            ["name" => "Europe/Vilnius", "code" => "Europe/Vilnius", "country" => CountryFixtures::COUNTRY_LITHUANIA],
            ["name" => "Europe/Copenhagen", "code" => "Europe/Copenhagen", "country" => CountryFixtures::COUNTRY_DENMARK],
            ["name" => "Europe/Riga", "code" => "Europe/Riga", "country" => CountryFixtures::COUNTRY_LATVIA],
        ];

        foreach ($timezonesData as $index => $timezoneData) {
            $timezone = new Timezone();
            $timezone->setCountry($this->getReference($timezoneData["country"], Country::class));
            $timezone->setName($timezoneData["name"]);
            $timezone->setCode($timezoneData["code"]);
            $manager->persist($timezone);

            if ($index === 0) {
                $this->addReference(self::TIMEZONE_EUROPE_ZAGREB, $timezone);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CountryFixtures::class
        ];
    }
}
