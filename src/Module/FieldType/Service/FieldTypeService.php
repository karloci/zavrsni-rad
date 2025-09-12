<?php

namespace App\Module\FieldType\Service;

use App\Entity\FieldType;
use App\Module\FieldType\Dto\FieldTypeDto;
use App\Module\FieldType\Repository\FieldTypeRepository;
use App\Service\ServiceLocator;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FieldTypeService
{
    private ServiceLocator $serviceLocator;
    private FieldTypeRepository $fieldTypeRepository;

    public function __construct(ServiceLocator $serviceLocator, FieldTypeRepository $fieldTypeRepository)
    {
        $this->serviceLocator = $serviceLocator;
        $this->fieldTypeRepository = $fieldTypeRepository;
    }

    /**
     * @return FieldType[]
     */
    public function findAllFieldTypesAction(): array
    {
        $fieldTypes = $this->fieldTypeRepository->findAllFieldTypes();

        $result = [];
        foreach ($fieldTypes as $fieldType) {
            if ($this->serviceLocator->security->isGranted("READ", $fieldType)) {
                $result[] = $fieldType;
            }
        }

        return $result;
    }

    public function createFieldTypeAction(FieldTypeDto $fieldTypeDto): FieldType
    {
        if (!$this->serviceLocator->security->isGranted("ROLE_ADMIN")) {
            throw new AccessDeniedHttpException();
        }

        try {
            $fieldType = new FieldType();
            $fieldType->setName($fieldTypeDto->getName());

            $this->fieldTypeRepository->save($fieldType, true);

            return $fieldType;
        }
        catch (UniqueConstraintViolationException) {
            throw new ConflictHttpException();
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }

    public function findOneFieldTypeAction(string $fieldTypeId): FieldType
    {
        $fieldType = $this->fieldTypeRepository->findOneFieldType($fieldTypeId);

        if (is_null($fieldType)) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("READ", $fieldType)) {
            throw new AccessDeniedHttpException();
        }

        return $fieldType;
    }

    public function updateFieldTypeAction(string $fieldTypeId, FieldTypeDto $fieldTypeDto): FieldType
    {
        $fieldType = $this->fieldTypeRepository->findOneFieldType($fieldTypeId);

        if (is_null($fieldType)) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("UPDATE", $fieldType)) {
            throw new AccessDeniedHttpException();
        }

        try {
            $fieldType->setName($fieldTypeDto->getName());

            $this->fieldTypeRepository->save($fieldType, true);
        }
        catch (UniqueConstraintViolationException) {
            throw new ConflictHttpException();
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        return $fieldType;
    }

    public function deleteFieldTypeAction(string $fieldTypeId): void
    {
        $fieldType = $this->fieldTypeRepository->findOneFieldType($fieldTypeId);

        if (is_null($fieldType)) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("DELETE", $fieldType)) {
            throw new AccessDeniedHttpException();
        }

        if ($fieldType->isDeleted()) {
            return;
        }

        try {
            $fieldType->markAsDeleted();
            $this->fieldTypeRepository->save($fieldType, true);
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }
}