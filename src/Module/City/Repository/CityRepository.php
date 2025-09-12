<?php

namespace App\Module\City\Repository;

use App\Repository\AbstractRepository;
use App\Entity\City;
use Doctrine\Persistence\ManagerRegistry;

class CityRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    /**
     * @param string $countryId
     * @return array
     */
    public function findAllCities(string $countryId): array
    {
        return $this->createQueryBuilder("city")
            ->andWhere("city.deletedAt IS NULL")
            ->andWhere("city.country = :countryId")
            ->setParameter("countryId", $countryId)
            ->orderBy("city.name", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function findOneCity(string $cityId): ?City
    {
        return $this->createQueryBuilder("city")
            ->addSelect("country")
            ->leftJoin("city.country", "country")
            ->andWhere("city.deletedAt IS NULL")
            ->andWhere("city.id = :cityId")
            ->setParameter("cityId", $cityId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
