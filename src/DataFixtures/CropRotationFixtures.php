<?php

namespace App\DataFixtures;

use App\Entity\Crop;
use App\Entity\CropRotation;
use App\Entity\Field;
use App\Entity\Season;
use App\Entity\User;
use DateInterval;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CropRotationFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $startDate = new DateTimeImmutable();
        $endDate = clone $startDate;
        $endDate->add(DateInterval::createFromDateString("+2 months"));

        $cropRotation = new CropRotation();
        $cropRotation->setSeason($this->getReference(SeasonFixtures::SEASON_TEST, Season::class));
        $cropRotation->setField($this->getReference(FieldFixtures::FIELD_TEST, Field::class));
        $cropRotation->setCrop($this->getReference(CropFixtures::CROP_WHEAT, Crop::class));
        $cropRotation->setPlantingDate($startDate);
        $cropRotation->setHarvestDate($endDate);
        $cropRotation->setCreatedBy($this->getReference(MainFixtures::USER_ADMIN, User::class));

        $manager->persist($cropRotation);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SeasonFixtures::class,
            FieldFixtures::class,
            CropFixtures::class,
            MainFixtures::class
        ];
    }
}
