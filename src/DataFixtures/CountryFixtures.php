<?php

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CountryFixtures extends Fixture
{
    public const string COUNTRY_CROATIA = "COUNTRY_CROATIA";
    public const string COUNTRY_SERBIA = "COUNTRY_SERBIA";
    public const string COUNTRY_SLOVENIA = "COUNTRY_SLOVENIA";
    public const string COUNTRY_GERMANY = "COUNTRY_GERMANY";
    public const string COUNTRY_FRANCE = "COUNTRY_FRANCE";
    public const string COUNTRY_NETHERLANDS = "COUNTRY_NETHERLANDS";
    public const string COUNTRY_ITALY = "COUNTRY_ITALY";
    public const string COUNTRY_SPAIN = "COUNTRY_SPAIN";
    public const string COUNTRY_POLAND = "COUNTRY_POLAND";
    public const string COUNTRY_AUSTRIA = "COUNTRY_AUSTRIA";
    public const string COUNTRY_HUNGARY = "COUNTRY_HUNGARY";
    public const string COUNTRY_ROMANIA = "COUNTRY_ROMANIA";
    public const string COUNTRY_PORTUGAL = "COUNTRY_PORTUGAL";
    public const string COUNTRY_GREECE = "COUNTRY_GREECE";
    public const string COUNTRY_BULGARIA = "COUNTRY_BULGARIA";
    public const string COUNTRY_CZECH_REPUBLIC = "COUNTRY_CZECH_REPUBLIC";
    public const string COUNTRY_BELGIUM = "COUNTRY_BELGIUM";
    public const string COUNTRY_LITHUANIA = "COUNTRY_LITHUANIA";
    public const string COUNTRY_DENMARK = "COUNTRY_DENMARK";
    public const string COUNTRY_LATVIA = "COUNTRY_LATVIA";

    public function load(ObjectManager $manager): void
    {
        $countries = [
            ["name" => "Croatia", "code" => "HRV", "reference" => self::COUNTRY_CROATIA],
            ["name" => "Serbia", "code" => "SRB", "reference" => self::COUNTRY_SERBIA],
            ["name" => "Slovenia", "code" => "SVN", "reference" => self::COUNTRY_SLOVENIA],
            ["name" => "Germany", "code" => "DEU", "reference" => self::COUNTRY_GERMANY],
            ["name" => "France", "code" => "FRA", "reference" => self::COUNTRY_FRANCE],
            ["name" => "Netherlands", "code" => "NLD", "reference" => self::COUNTRY_NETHERLANDS],
            ["name" => "Italy", "code" => "ITA", "reference" => self::COUNTRY_ITALY],
            ["name" => "Spain", "code" => "ESP", "reference" => self::COUNTRY_SPAIN],
            ["name" => "Poland", "code" => "POL", "reference" => self::COUNTRY_POLAND],
            ["name" => "Austria", "code" => "AUT", "reference" => self::COUNTRY_AUSTRIA],
            ["name" => "Hungary", "code" => "HUN", "reference" => self::COUNTRY_HUNGARY],
            ["name" => "Romania", "code" => "ROU", "reference" => self::COUNTRY_ROMANIA],
            ["name" => "Portugal", "code" => "PRT", "reference" => self::COUNTRY_PORTUGAL],
            ["name" => "Greece", "code" => "GRC", "reference" => self::COUNTRY_GREECE],
            ["name" => "Bulgaria", "code" => "BGR", "reference" => self::COUNTRY_BULGARIA],
            ["name" => "Czech Republic", "code" => "CZE", "reference" => self::COUNTRY_CZECH_REPUBLIC],
            ["name" => "Belgium", "code" => "BEL", "reference" => self::COUNTRY_BELGIUM],
            ["name" => "Lithuania", "code" => "LTU", "reference" => self::COUNTRY_LITHUANIA],
            ["name" => "Denmark", "code" => "DNK", "reference" => self::COUNTRY_DENMARK],
            ["name" => "Latvia", "code" => "LVA", "reference" => self::COUNTRY_LATVIA],
        ];

        foreach ($countries as $countryData) {
            $country = new Country();
            $country->setName($countryData["name"]);
            $country->setCode($countryData["code"]);
            $manager->persist($country);

            $this->addReference($countryData["reference"], $country);
        }

        $manager->flush();
    }
}
