<?php

namespace App\DataFixtures;

use App\Entity\Farm;
use App\Entity\Field;
use App\Entity\FieldType;
use App\Entity\SoilType;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FieldFixtures extends Fixture implements DependentFixtureInterface
{
    public const string FIELD_TEST = "FIELD_TEST";

    public function load(ObjectManager $manager): void
    {
        $fields = [
            ["name" => "Field #1", "area" => 20],
            ["name" => "Field #2", "area" => 35],
            ["name" => "Field #3", "area" => 50],
        ];

        foreach ($fields as $key => $fieldData) {
            $field = new Field();
            $field->setFarm($this->getReference(FarmFixtures::FARM_TEST, Farm::class));

            switch ($fieldData["name"]) {
                case "Field #1":
                    $field->setFieldType($this->getReference(FieldTypeFixtures::FIELD_TYPE_OUTDOOR, FieldType::class));
                    $field->setSoilType($this->getReference(SoilTypeFixtures::SOIL_TYPE_BLACK_SOIL, SoilType::class));
                    break;
                case "Field #2":
                    $field->setFieldType($this->getReference(FieldTypeFixtures::FIELD_TYPE_GREENHOUSE, FieldType::class));
                    $field->setSoilType($this->getReference(SoilTypeFixtures::SOIL_TYPE_RED_SOIL, SoilType::class));
                    break;
                case "Field #3":
                    $field->setFieldType($this->getReference(FieldTypeFixtures::FIELD_TYPE_GROW_TENT, FieldType::class));
                    $field->setSoilType($this->getReference(SoilTypeFixtures::SOIL_TYPE_GRAY_SOIL, SoilType::class));
                    break;
                default:
                    $field->setFieldType($this->getReference(FieldTypeFixtures::FIELD_TYPE_GREENHOUSE, FieldType::class));
                    $field->setSoilType($this->getReference(SoilTypeFixtures::SOIL_TYPE_BLACK_SOIL, SoilType::class));
            }

            $field->setName($fieldData["name"]);
            $field->setArea($fieldData["area"]);
            $field->setCreatedBy($this->getReference(MainFixtures::USER_ADMIN, User::class));

            $manager->persist($field);

            if ($key === 0) {
                $this->addReference(self::FIELD_TEST, $field);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            FarmFixtures::class,
            FieldTypeFixtures::class,
            SoilTypeFixtures::class,
            MainFixtures::class
        ];
    }
}
