<?php

namespace App\Module\Country\Repository;

use App\Repository\AbstractRepository;
use App\Entity\Country;
use Doctrine\Persistence\ManagerRegistry;

class CountryRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    /**
     * @return Country[]
     */
    public function findAllCountries(): array
    {
        return $this->createQueryBuilder("country")
            ->andWhere("country.deletedAt IS NULL")
            ->orderBy("country.name", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function findOneCountry(string $countryId): ?Country
    {
        return $this->createQueryBuilder("country")
            ->addSelect("city", "timezone")
            ->leftJoin("country.cities", "city")
            ->leftJoin("country.timezones", "timezone")
            ->andWhere("country.deletedAt IS NULL")
            ->andWhere("country.id = :countryId")
            ->setParameter("countryId", $countryId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
