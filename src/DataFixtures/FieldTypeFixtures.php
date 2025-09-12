<?php

namespace App\DataFixtures;

use App\Entity\FieldType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FieldTypeFixtures extends Fixture
{
    public const string FIELD_TYPE_OUTDOOR = "FIELD_TYPE_OUTDOOR";
    public const string FIELD_TYPE_GREENHOUSE = "FIELD_TYPE_GREENHOUSE";
    public const string FIELD_TYPE_GROW_TENT = "FIELD_TYPE_GROW_TENT";

    public function load(ObjectManager $manager): void
    {
        $fieldTypes = [
            ["name" => "Outdoor"],          // Otvoreno polje - klasično poljoprivredno polje na otvorenom
            ["name" => "Greenhouse"],       // Staklenik - kontrolirani uvjeti za uzgoj povrća i cvijeća
            ["name" => "Grow tent"],        // Unutarnji uzgoj - mali zatvoreni sustavi za specijalizirani uzgoj
            ["name" => "Orchard"],          // Voćnjak - uzgoj voćaka (jabuke, trešnje, masline)
            ["name" => "Vineyard"],         // Vinograd - uzgoj vinove loze za proizvodnju vina
            ["name" => "Hydroponic"],       // Hidroponska farma - uzgoj biljaka bez tla, samo u vodi s nutrijentima
            ["name" => "Aquaponic"],        // Akvaponska farma - kombinacija uzgoja riba i biljaka
            ["name" => "Horticultural"],    // Hortikulturno polje - uzgoj povrća, cvijeća i ukrasnog bilja
            ["name" => "Organic"],          // Organska farma - uzgoj bez sintetičkih pesticida i gnojiva
            ["name" => "Experimental"]      // Eksperimentalno polje - istraživačke i testne plantaže
        ];

        foreach ($fieldTypes as $fieldTypeData) {
            $fieldType = new FieldType();
            $fieldType->setName($fieldTypeData["name"]);

            $manager->persist($fieldType);

            if ($fieldTypeData["name"] === "Outdoor") {
                $this->addReference(self::FIELD_TYPE_OUTDOOR, $fieldType);
            }
            else {
                if ($fieldTypeData["name"] === "Greenhouse") {
                    $this->addReference(self::FIELD_TYPE_GREENHOUSE, $fieldType);
                }
                else {
                    if ($fieldTypeData["name"] === "Grow tent") {
                        $this->addReference(self::FIELD_TYPE_GROW_TENT, $fieldType);
                    }
                }
            }
        }

        $manager->flush();
    }
}
