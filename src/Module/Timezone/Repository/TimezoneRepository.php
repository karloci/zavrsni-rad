<?php

namespace App\Module\Timezone\Repository;

use App\Repository\AbstractRepository;
use App\Entity\Timezone;
use Doctrine\Persistence\ManagerRegistry;

class TimezoneRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Timezone::class);
    }

    /**
     * @param string $countryId
     * @return array
     */
    public function findAllTimezones(string $countryId): array
    {
        return $this->createQueryBuilder("timezone")
            ->andWhere("timezone.deletedAt IS NULL")
            ->andWhere("timezone.country = :countryId")
            ->setParameter("countryId", $countryId)
            ->orderBy("timezone.name", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function findOneTimezone(string $timezoneId): ?Timezone
    {
        return $this->createQueryBuilder("timezone")
            ->addSelect("country")
            ->leftJoin("timezone.country", "country")
            ->andWhere("timezone.deletedAt IS NULL")
            ->andWhere("timezone.id = :timezoneId")
            ->setParameter("timezoneId", $timezoneId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
