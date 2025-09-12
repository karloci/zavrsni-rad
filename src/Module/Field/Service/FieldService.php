<?php

namespace App\Module\Field\Service;

use App\Entity\Farm;
use App\Entity\Field;
use App\Entity\FieldType;
use App\Entity\SoilType;
use App\Module\Field\Dto\FieldDto;
use App\Module\Field\Exception\UniqueFieldException;
use App\Module\Field\Repository\FieldRepository;
use App\Service\ServiceLocator;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FieldService
{
    private ServiceLocator $serviceLocator;
    private FieldRepository $fieldRepository;

    public function __construct(ServiceLocator $serviceLocator, FieldRepository $fieldRepository)
    {
        $this->serviceLocator = $serviceLocator;
        $this->fieldRepository = $fieldRepository;
    }

    /**
     * @param Farm $farm
     *
     * @return Field[]
     */
    public function findAllFieldsAction(Farm $farm): array
    {
        if (!$this->serviceLocator->security->isGranted("READ", $farm)) {
            throw new AccessDeniedHttpException();
        }

        $fields = $this->fieldRepository->findAllFields($farm);

        $result = [];
        foreach ($fields as $field) {
            if ($this->serviceLocator->security->isGranted("READ", $field)) {
                $result[] = $field;
            }
        }

        return $result;
    }

    public function createFieldAction(Farm $farm, FieldDto $fieldDto): Field
    {
        if (!$this->serviceLocator->security->isGranted("READ", $farm)) {
            throw new AccessDeniedHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("ROLE_OWNER")) {
            throw new AccessDeniedHttpException();
        }

        try {
            $field = new Field();
            $field->setFarm($farm);
            $field->setFieldType($this->serviceLocator->entityManager->getReference(FieldType::class, $fieldDto->getFieldType()));
            $field->setSoilType($this->serviceLocator->entityManager->getReference(SoilType::class, $fieldDto->getSoilType()));
            $field->setName($fieldDto->getName());
            $field->setArea($fieldDto->getArea());
            $field->setCreatedBy($this->serviceLocator->security->getUser());

            $this->fieldRepository->save($field, true);

            return $field;
        }
        catch (UniqueConstraintViolationException) {
            throw new UniqueFieldException("The field with this name already exists on farm");
        }
        catch (ORMException $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }

    public function findOneFieldAction(Farm $farm, string $fieldId): Field
    {
        if (!$this->serviceLocator->security->isGranted("READ", $farm)) {
            throw new AccessDeniedHttpException();
        }

        $field = $this->fieldRepository->findOneField($fieldId);

        if (is_null($field)) {
            throw new NotFoundHttpException();
        }

        if ($field->getFarm() !== $farm) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("READ", $field)) {
            throw new AccessDeniedHttpException();
        }

        return $field;
    }

    public function updateFieldAction(Farm $farm, string $fieldId, FieldDto $fieldDto): Field
    {
        if (!$this->serviceLocator->security->isGranted("READ", $farm)) {
            throw new AccessDeniedHttpException();
        }

        $field = $this->fieldRepository->findOneField($fieldId);

        if (is_null($field)) {
            throw new NotFoundHttpException();
        }

        if ($field->getFarm() !== $farm) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("UPDATE", $field)) {
            throw new AccessDeniedHttpException();
        }

        try {
            $field->setFarm($farm);
            $field->setFieldType($this->serviceLocator->entityManager->getReference(FieldType::class, $fieldDto->getFieldType()));
            $field->setSoilType($this->serviceLocator->entityManager->getReference(SoilType::class, $fieldDto->getSoilType()));
            $field->setName($fieldDto->getName());
            $field->setArea($fieldDto->getArea());
            $field->setUpdatedBy($this->serviceLocator->security->getUser());

            $this->fieldRepository->save($field, true);

            return $field;
        }
        catch (UniqueConstraintViolationException) {
            throw new UniqueFieldException("The field with this name already exists on farm");
        }
        catch (ORMException $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }

    public function deleteFieldAction(Farm $farm, string $fieldId): void
    {
        if (!$this->serviceLocator->security->isGranted("READ", $farm)) {
            throw new AccessDeniedHttpException();
        }

        $field = $this->fieldRepository->findOneField($fieldId);

        if (is_null($field)) {
            throw new NotFoundHttpException();
        }

        if ($field->getFarm() !== $farm) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("DELETE", $field)) {
            throw new AccessDeniedHttpException();
        }

        try {
            if (!$field->isDeleted()) {
                $field->markAsDeleted($this->serviceLocator->security->getUser());
                $this->fieldRepository->save($field, true);
            }
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }
}