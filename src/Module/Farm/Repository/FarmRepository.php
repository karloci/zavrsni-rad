<?php

namespace App\Module\Farm\Repository;

use App\Repository\AbstractRepository;
use App\Entity\Farm;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

class FarmRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Farm::class);
    }

    /**
     * @return Farm[]
     */
    public function findAllFarms(): array
    {
        return $this->createQueryBuilder("farm")
            ->orderBy("farm.name", "ASC")
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Farm[]
     */
    public function findAllFarmsByUser(User $user): array
    {
        return $this->createQueryBuilder("farm")
            ->addSelect("user")
            ->leftJoin("farm.users", "user")
            ->andWhere("user.id = :userId")
            ->setParameter("userId", $user->getId())
            ->orderBy("farm.name", "ASC")
            ->getQuery()
            ->getResult();
    }

    public function findOneFarm(string $farmId): ?Farm
    {
        return $this->createQueryBuilder("farm")
            ->addSelect("country", "city", "timezone", "field")
            ->leftJoin("farm.country", "country")
            ->leftJoin("farm.city", "city")
            ->leftJoin("farm.timezone", "timezone")
            ->leftJoin("farm.fields", "field")
            ->andWhere("farm.id = :farmId")
            ->setParameter("farmId", $farmId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
