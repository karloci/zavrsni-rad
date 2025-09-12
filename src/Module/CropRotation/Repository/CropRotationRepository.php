<?php

namespace App\Module\CropRotation\Repository;

use App\Repository\AbstractRepository;
use App\Entity\CropRotation;
use App\Entity\Farm;
use Doctrine\Persistence\ManagerRegistry;

class CropRotationRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CropRotation::class);
    }

    public function findAllCropRotationsByFarm(Farm $farm)
    {
        return $this->createQueryBuilder("cropRotation")
            ->addSelect("field", "season", "crop")
            ->leftJoin("cropRotation.field", "field")
            ->leftJoin("cropRotation.season", "season")
            ->leftJoin("cropRotation.crop", "crop")
            ->andWhere("field.farm = :farm")
            ->andWhere("cropRotation.season IS NOT NULL")
            ->setParameter("farm", $farm)
            ->getQuery()
            ->getResult();
    }

    public function findOneCropRotation(string $cropRotationId): ?CropRotation
    {
        return $this->createQueryBuilder("cropRotation")
            ->addSelect("field", "season", "crop", "createdBy", "updatedBy")
            ->leftJoin("cropRotation.field", "field")
            ->leftJoin("cropRotation.season", "season")
            ->leftJoin("cropRotation.crop", "crop")
            ->leftJoin("cropRotation.createdBy", "createdBy")
            ->leftJoin("cropRotation.updatedBy", "updatedBy")
            ->andWhere("cropRotation.id = :id")
            ->setParameter("id", $cropRotationId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}