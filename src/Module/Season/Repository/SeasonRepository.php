<?php

namespace App\Module\Season\Repository;

use App\Repository\AbstractRepository;
use App\Entity\Farm;
use App\Entity\Season;
use Doctrine\Persistence\ManagerRegistry;

class SeasonRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Season::class);
    }

    /**
     * @param Farm $farm
     *
     * @return Season[]
     */
    public function findAllSeasons(Farm $farm): array
    {
        return $this->createQueryBuilder("season")
            ->andWhere("season.farm = :farm")
            ->andWhere("season.deletedAt IS NULL")
            ->addOrderBy("season.startDate")
            ->addOrderBy("season.name")
            ->setParameter("farm", $farm)
            ->getQuery()
            ->getResult();
    }

    public function findOneSeason(string $seasonId): ?Season
    {
        return $this->createQueryBuilder("season")
            ->addSelect("createdBy", "updatedBy")
            ->leftJoin("season.createdBy", "createdBy")
            ->leftJoin("season.updatedBy", "updatedBy")
            ->andWhere("season.id = :id")
            ->andWhere("season.deletedAt IS NULL")
            ->setParameter("id", $seasonId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}