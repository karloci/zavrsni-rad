<?php

namespace App\Module\FieldType\Repository;

use App\Repository\AbstractRepository;
use App\Entity\FieldType;
use Doctrine\Persistence\ManagerRegistry;

class FieldTypeRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FieldType::class);
    }

    /**
     * @return FieldType[]
     */
    public function findAllFieldTypes(): array
    {
        return $this->createQueryBuilder("fieldType")
            ->andWhere("fieldType.deletedAt IS NULL")
            ->orderBy("fieldType.name")
            ->getQuery()
            ->getResult();
    }

    public function findOneFieldType(string $fieldTypeId): ?FieldType
    {
        return $this->createQueryBuilder("fieldType")
            ->andWhere("fieldType.deletedAt IS NULL")
            ->andWhere("fieldType.id = :fieldType")
            ->setParameter("fieldType", $fieldTypeId)
            ->orderBy("fieldType.name")
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
