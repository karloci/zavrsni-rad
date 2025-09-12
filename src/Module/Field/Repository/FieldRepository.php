<?php

namespace App\Module\Field\Repository;

use App\Repository\AbstractRepository;
use App\Entity\Farm;
use App\Entity\Field;
use Doctrine\Persistence\ManagerRegistry;

class FieldRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Field::class);
    }

    /**
     * @param Farm $farm
     *
     * @return Field[]
     */
    public function findAllFields(Farm $farm): array
    {
        return $this->createQueryBuilder("field")
            ->addSelect("fieldType", "soilType")
            ->leftJoin("field.fieldType", "fieldType")
            ->leftJoin("field.soilType", "soilType")
            ->andWhere("field.farm = :farm")
            ->andWhere("field.deletedAt IS NULL")
            ->setParameter("farm", $farm)
            ->getQuery()
            ->getResult();
    }

    public function findOneField(string $fieldId): ?Field
    {
        return $this->createQueryBuilder("field")
            ->addSelect("fieldType", "soilType", "createdBy", "updatedBy")
            ->leftJoin("field.fieldType", "fieldType")
            ->leftJoin("field.soilType", "soilType")
            ->leftJoin("field.createdBy", "createdBy")
            ->leftJoin("field.updatedBy", "updatedBy")
            ->andWhere("field.id = :id")
            ->andWhere("field.deletedAt IS NULL")
            ->setParameter("id", $fieldId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
