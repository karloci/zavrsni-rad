<?php

namespace App\Module\Crop\Repository;

use App\Repository\AbstractRepository;
use App\Entity\Crop;
use Doctrine\Persistence\ManagerRegistry;

class CropRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Crop::class);
    }

    /**
     * @return Crop[]
     */
    public function findAllCrops(): array
    {
        return $this->createQueryBuilder("crop")
            ->andWhere("crop.deletedAt IS NULL")
            ->orderBy("crop.name", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function findOneCrop(string $cropId): ?Crop
    {
        return $this->createQueryBuilder("crop")
            ->andWhere("crop.deletedAt IS NULL")
            ->andWhere("crop.id = :cropId")
            ->setParameter("cropId", $cropId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
