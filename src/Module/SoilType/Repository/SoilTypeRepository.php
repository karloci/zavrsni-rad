<?php

namespace App\Module\SoilType\Repository;

use App\Repository\AbstractRepository;
use App\Entity\SoilType;
use Doctrine\Persistence\ManagerRegistry;

class SoilTypeRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SoilType::class);
    }

    /**
     * @return SoilType[]
     */
    public function findAllSoilTypes(): array
    {
        return $this->createQueryBuilder("soilType")
            ->andWhere("soilType.deletedAt IS NULL")
            ->orderBy("soilType.name")
            ->getQuery()
            ->getResult();
    }

    public function findOneSoilType(string $soilTypeId): ?SoilType
    {
        return $this->createQueryBuilder("soilType")
            ->andWhere("soilType.deletedAt IS NULL")
            ->andWhere("soilType.id = :soilTypeId")
            ->setParameter("soilTypeId", $soilTypeId)
            ->orderBy("soilType.name")
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
