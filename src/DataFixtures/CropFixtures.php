<?php

namespace App\DataFixtures;

use App\Entity\Crop;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CropFixtures extends Fixture
{
    public const string CROP_WHEAT = "CROP_WHEAT";

    public function load(ObjectManager $manager): void
    {
        $crops = [
            ["name" => "Wheat"],
            ["name" => "Corn"],
            ["name" => "Soybean"],
            ["name" => "Sunflower"],
            ["name" => "Rapeseed"],
            ["name" => "Sugar Beet"],
            ["name" => "Tobacco"],
            ["name" => "Barley"],
            ["name" => "Rye"],
            ["name" => "Clover"],
            ["name" => "Apple"],
            ["name" => "Plum"],
            ["name" => "Peach"],
            ["name" => "Sour Cherry"],
            ["name" => "Apricot"],
            ["name" => "Walnut"],
            ["name" => "Pear"],
            ["name" => "Cherry"],
            ["name" => "Quince"],
            ["name" => "Hazelnut"],
            ["name" => "Strawberry"],
            ["name" => "Raspberry"],
            ["name" => "Blackberry"],
            ["name" => "Blueberry"],
            ["name" => "Potato"],
            ["name" => "Pepper"],
            ["name" => "Tomato"],
            ["name" => "Cabbage"],
            ["name" => "Carrot"],
            ["name" => "Bean"],
            ["name" => "Pea"],
            ["name" => "Cucumber"],
            ["name" => "Watermelon"],
            ["name" => "Melon"],
            ["name" => "Pumpkin"],
            ["name" => "Spinach"],
            ["name" => "Broccoli"],
            ["name" => "Cauliflower"],
        ];

        foreach ($crops as $key => $cropData) {
            $crop = new Crop();
            $crop->setName($cropData["name"]);
            $manager->persist($crop);

            if ($key === 0) {
                $this->addReference(self::CROP_WHEAT, $crop);
            }
        }

        $manager->flush();
    }
}
