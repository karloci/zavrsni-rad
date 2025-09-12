<?php

namespace App\DataFixtures;

use App\Entity\SoilType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SoilTypeFixtures extends Fixture
{
    public const string SOIL_TYPE_BLACK_SOIL = "SOIL_TYPE_BLACK_SOIL";
    public const string SOIL_TYPE_RED_SOIL = "SOIL_TYPE_RED_SOIL";
    public const string SOIL_TYPE_GRAY_SOIL = "SOIL_TYPE_GRAY_SOIL";

    public function load(ObjectManager $manager): void
    {
        $soilTypes = [
            ["name" => "Sandy"],        // Pjeskovito tlo - dobro za kulture koje vole dobru drenažu (mrkva, lubenica)
            ["name" => "Clay"],         // Glineno tlo - zadržava vlagu, ali može biti teško za obradu (pšenica, soja)
            ["name" => "Silty"],        // Muljevito tlo - plodno i dobro za poljoprivredu (povrće, voće)
            ["name" => "Peaty"],        // Tresetno tlo - bogato organskom tvari, ali kiselo (borovnice, riža)
            ["name" => "Loamy"],        // Ilovasto tlo - idealno za većinu kultura zbog dobre strukture (kukuruz, povrće)
            ["name" => "Chalky"],       // Vapnenačko tlo - pH visoko, pogodno za grožđe i lavandu
            ["name" => "Saline"],       // Slano tlo - ograničena upotreba, ali pogodno za halofitne biljke
            ["name" => "Alluvial"],     // Naplavno tlo - iznimno plodno, koristi se za uzgoj riže i povrća
            ["name" => "Black soil"],   // Crno tlo - bogato humusom, idealno za pamuk i suncokret
            ["name" => "Red soil"],     // Crveno tlo - bogato željezom, koristi se za mahunarke i kikiriki
            ["name" => "Volcanic"],     // Vulkanisko tlo - iznimno plodno, koristi se za kavu i voćke
            ["name" => "Lateritic"],    // Lateritno tlo - kiselo i siromašno nutrijentima, koristi se za tropske kulture
            ["name" => "Marl"],         // Laporasto tlo - sadrži vapnenac i glinu, dobro za vinovu lozu
            ["name" => "Gray soil"],    // Sivo tlo - često siromašno nutrijentima, koristi se uz gnojidbu
        ];

        foreach ($soilTypes as $soilTypeData) {
            $soilType = new SoilType();
            $soilType->setName($soilTypeData["name"]);

            $manager->persist($soilType);

            if ($soilTypeData["name"] === "Black soil") {
                $this->addReference(self::SOIL_TYPE_BLACK_SOIL, $soilType);
            }
            else {
                if ($soilTypeData["name"] === "Red soil") {
                    $this->addReference(self::SOIL_TYPE_RED_SOIL, $soilType);

                }
                else {
                    if ($soilTypeData["name"] === "Gray soil") {
                        $this->addReference(self::SOIL_TYPE_GRAY_SOIL, $soilType);
                    }
                }
            }
        }

        $manager->flush();
    }
}
