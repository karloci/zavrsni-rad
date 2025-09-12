<?php

namespace App\Module\Crop\Service;

use App\Entity\Crop;
use App\Module\Crop\Dto\CropDto;
use App\Module\Crop\Repository\CropRepository;
use App\Service\ServiceLocator;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CropService
{
    private ServiceLocator $serviceLocator;
    private CropRepository $cropRepository;

    public function __construct(ServiceLocator $serviceLocator, CropRepository $cropRepository)
    {
        $this->serviceLocator = $serviceLocator;
        $this->cropRepository = $cropRepository;
    }

    /**
     * @return Crop[]
     */
    public function findAllCropsAction(): array
    {
        $crops = $this->cropRepository->findAllCrops();

        $result = [];
        foreach ($crops as $crop) {
            if ($this->serviceLocator->security->isGranted("READ", $crop)) {
                $result[] = $crop;
            }
        }

        return $result;
    }

    public function createCropAction(CropDto $cropDto): Crop
    {
        if (!$this->serviceLocator->security->isGranted("ROLE_ADMIN")) {
            throw new AccessDeniedHttpException();
        }

        try {
            $crop = new Crop();
            $crop->setName($cropDto->getName());

            $this->cropRepository->save($crop, true);

            return $crop;
        }
        catch (UniqueConstraintViolationException) {
            throw new ConflictHttpException();
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }

    public function findOneCropAction(string $cropId): Crop
    {
        $crop = $this->cropRepository->findOneCrop($cropId);

        if (is_null($crop)) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("READ", $crop)) {
            throw new AccessDeniedHttpException();
        }

        return $crop;
    }

    public function updateCropAction(string $cropId, CropDto $cropDto): Crop
    {
        $crop = $this->cropRepository->findOneCrop($cropId);

        if (is_null($crop)) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("UPDATE", $crop)) {
            throw new AccessDeniedHttpException();
        }

        try {
            $crop->setName($cropDto->getName());

            $this->cropRepository->save($crop, true);
        }
        catch (UniqueConstraintViolationException) {
            throw new ConflictHttpException();
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        return $crop;
    }

    public function deleteCropAction(string $cropId): void
    {
        /** @var Crop $crop */
        $crop = $this->cropRepository->findOneCrop($cropId);

        if (is_null($crop)) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("DELETE", $crop)) {
            throw new AccessDeniedHttpException();
        }

        if ($crop->isDeleted()) {
            return;
        }

        try {
            $crop->markAsDeleted();
            $this->cropRepository->save($crop, true);
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }
}