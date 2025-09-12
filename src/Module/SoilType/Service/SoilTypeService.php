<?php

namespace App\Module\SoilType\Service;

use App\Entity\SoilType;
use App\Module\SoilType\Dto\SoilTypeDto;
use App\Module\SoilType\Repository\SoilTypeRepository;
use App\Service\ServiceLocator;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SoilTypeService
{
    private ServiceLocator $serviceLocator;
    private SoilTypeRepository $soilTypeRepository;

    public function __construct(ServiceLocator $serviceLocator, SoilTypeRepository $soilTypeRepository)
    {
        $this->serviceLocator = $serviceLocator;
        $this->soilTypeRepository = $soilTypeRepository;
    }

    /**
     * @return SoilType[]
     */
    public function findAllSoilTypesAction(): array
    {
        $soilTypes = $this->soilTypeRepository->findAllSoilTypes();

        $result = [];
        foreach ($soilTypes as $soilType) {
            if ($this->serviceLocator->security->isGranted("READ", $soilType)) {
                $result[] = $soilType;
            }
        }

        return $result;
    }

    public function createSoilTypeAction(SoilTypeDto $soilTypeDto): SoilType
    {
        if (!$this->serviceLocator->security->isGranted("ROLE_ADMIN")) {
            throw new AccessDeniedHttpException();
        }

        try {
            $soilType = new SoilType();
            $soilType->setName($soilTypeDto->getName());

            $this->soilTypeRepository->save($soilType, true);

            return $soilType;
        }
        catch (UniqueConstraintViolationException) {
            throw new ConflictHttpException();
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }

    public function findOneSoilTypeAction(string $soilTypeId): SoilType
    {
        $soilType = $this->soilTypeRepository->findOneSoilType($soilTypeId);

        if (is_null($soilType)) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("READ", $soilType)) {
            throw new AccessDeniedHttpException();
        }

        return $soilType;
    }

    public function updateSoilTypeAction(string $soilTypeId, SoilTypeDto $soilTypeDto): SoilType
    {
        $soilType = $this->soilTypeRepository->findOneSoilType($soilTypeId);

        if (is_null($soilType)) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("UPDATE", $soilType)) {
            throw new AccessDeniedHttpException();
        }

        try {
            $soilType->setName($soilTypeDto->getName());

            $this->soilTypeRepository->save($soilType, true);
        }
        catch (UniqueConstraintViolationException) {
            throw new ConflictHttpException();
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        return $soilType;
    }

    public function deleteSoilTypeAction(string $soilTypeId): void
    {
        /** @var SoilType $soilType */
        $soilType = $this->soilTypeRepository->findOneSoilType($soilTypeId);

        if (is_null($soilType)) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("DELETE", $soilType)) {
            throw new AccessDeniedHttpException();
        }

        if ($soilType->isDeleted()) {
            return;
        }

        try {
            $soilType->markAsDeleted();
            $this->soilTypeRepository->save($soilType, true);
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }
}