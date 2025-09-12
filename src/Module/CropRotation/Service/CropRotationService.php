<?php

namespace App\Module\CropRotation\Service;

use App\Entity\Crop;
use App\Entity\CropRotation;
use App\Entity\Farm;
use App\Entity\Season;
use App\Module\CropRotation\Dto\CreateCropRotationDto;
use App\Module\CropRotation\Dto\UpdateCropRotationDto;
use App\Module\CropRotation\Repository\CropRotationRepository;
use App\Module\Field\Repository\FieldRepository;
use App\Service\ServiceLocator;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CropRotationService
{
    private ServiceLocator $serviceLocator;
    private CropRotationRepository $cropRotationRepository;
    private FieldRepository $fieldRepository;

    public function __construct(ServiceLocator $serviceLocator, CropRotationRepository $cropRotationRepository, FieldRepository $fieldRepository)
    {
        $this->serviceLocator = $serviceLocator;
        $this->cropRotationRepository = $cropRotationRepository;
        $this->fieldRepository = $fieldRepository;
    }

    /**
     * @param Farm $farm
     *
     * @return CropRotation[]
     */
    public function findAllCropRotationsAction(Farm $farm): array
    {
        if (!$this->serviceLocator->security->isGranted("READ", $farm)) {
            throw new AccessDeniedHttpException();
        }

        $cropRotationsByFarm = $this->cropRotationRepository->findAllCropRotationsByFarm($farm);

        $result = [];
        foreach ($cropRotationsByFarm as $cropRotation) {
            if ($this->serviceLocator->security->isGranted("READ", $cropRotation)) {
                $result[] = $cropRotation;
            }
        }

        return $result;
    }

    public function createCropRotationAction(Farm $farm, CreateCropRotationDto $createCropRotationDto): CropRotation
    {
        if (!$this->serviceLocator->security->isGranted("READ", $farm)) {
            throw new AccessDeniedHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("ROLE_OWNER")) {
            throw new AccessDeniedHttpException();
        }

        $field = $this->fieldRepository->findOneField($createCropRotationDto->getField());

        if (is_null($field)) {
            throw new BadRequestHttpException();
        }

        if ($field->getFarm() !== $farm) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("READ", $field)) {
            throw new AccessDeniedHttpException();
        }

        $plantingDate = $createCropRotationDto->getPlantingDate();
        $harvestDate = $createCropRotationDto->getHarvestDate();

        try {
            $cropRotation = new CropRotation();
            $cropRotation->setField($field);
            $cropRotation->setSeason($this->serviceLocator->entityManager->getReference(Season::class, $createCropRotationDto->getSeason()));
            $cropRotation->setCrop($this->serviceLocator->entityManager->getReference(Crop::class, $createCropRotationDto->getCrop()));
            $cropRotation->setPlantingDate($plantingDate);
            $cropRotation->setHarvestDate($harvestDate);
            $cropRotation->setCreatedBy($this->serviceLocator->security->getUser());

            $this->cropRotationRepository->save($cropRotation, true);

            return $cropRotation;
        }
        catch (ORMException $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }

    public function findOneCropRotationAction(Farm $farm, string $cropRotationId): CropRotation
    {
        if (!$this->serviceLocator->security->isGranted("READ", $farm)) {
            throw new AccessDeniedHttpException();
        }

        $cropRotation = $this->cropRotationRepository->findOneCropRotation($cropRotationId);

        if (is_null($cropRotation)) {
            throw new NotFoundHttpException();
        }

        if ($cropRotation->getField()->getFarm() !== $farm) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("READ", $cropRotation)) {
            throw new AccessDeniedHttpException();
        }

        return $cropRotation;
    }

    public function updateCropRotationAction(Farm $farm, string $cropRotationId, UpdateCropRotationDto $updateCropRotationDto): CropRotation
    {
        if (!$this->serviceLocator->security->isGranted("READ", $farm)) {
            throw new AccessDeniedHttpException();
        }

        $cropRotation = $this->cropRotationRepository->findOneCropRotation($cropRotationId);

        if (is_null($cropRotation)) {
            throw new NotFoundHttpException();
        }

        if ($cropRotation->getField()->getFarm() !== $farm) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("UPDATE", $cropRotation)) {
            throw new AccessDeniedHttpException();
        }

        $plantingDate = $updateCropRotationDto->getPlantingDate();
        $harvestDate = $updateCropRotationDto->getHarvestDate();

        try {
            $cropRotation->setPlantingDate($plantingDate);
            $cropRotation->setHarvestDate($harvestDate);
            $cropRotation->setUpdatedBy($this->serviceLocator->security->getUser());

            $this->cropRotationRepository->save($cropRotation, true);

            return $cropRotation;
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }

    public function deleteCropRotationAction(Farm $farm, string $cropRotationId): void
    {
        if (!$this->serviceLocator->security->isGranted("READ", $farm)) {
            throw new AccessDeniedHttpException();
        }

        $cropRotation = $this->cropRotationRepository->findOneCropRotation($cropRotationId);

        if (is_null($cropRotation)) {
            throw new NotFoundHttpException();
        }

        if ($cropRotation->getField()->getFarm() !== $farm) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("DELETE", $cropRotation)) {
            throw new AccessDeniedHttpException();
        }

        try {
            $this->cropRotationRepository->delete($cropRotation, true);
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }
}