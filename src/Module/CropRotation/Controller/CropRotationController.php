<?php

namespace App\Module\CropRotation\Controller;

use App\Controller\ApiController;
use App\Module\CropRotation\Dto\CreateCropRotationDto;
use App\Module\CropRotation\Dto\UpdateCropRotationDto;
use App\Entity\Farm;
use App\Module\CropRotation\Service\CropRotationService;
use App\Serializer\DataSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CropRotationController extends ApiController
{
    private CropRotationService $cropRotationService;

    public function __construct(DataSerializer $dataSerializer, CropRotationService $cropRotationService)
    {
        parent::__construct($dataSerializer);
        $this->cropRotationService = $cropRotationService;
    }

    #[Route("/farms/{farm}/crop-rotations", name: "farm_crop_rotations_list", methods: ["GET"])]
    public function findAllCropRotations(Farm $farm): JsonResponse
    {
        $cropRotations = $this->cropRotationService->findAllCropRotationsAction($farm);

        return $this->getHttpOkResponse($cropRotations, ["cropRotation:default", "cropRotation:season", "cropRotation:field", "cropRotation:crop"]);
    }

    #[Route("/farms/{farm}/crop-rotations", name: "farm_crop_rotations_create", methods: ["POST"])]
    public function createCropRotation(Farm $farm, #[MapRequestPayload] CreateCropRotationDto $createCropRotationDto): JsonResponse
    {
        $cropRotation = $this->cropRotationService->createCropRotationAction($farm, $createCropRotationDto);

        return $this->getHttpCreatedResponse($cropRotation, ["cropRotation:default", "cropRotation:season", "cropRotation:field", "cropRotation:crop"]);
    }

    #[Route("/farms/{farm}/crop-rotations/{cropRotationId}", name: "farm_crop_rotations_show", methods: ["GET"])]
    public function findOneCropRotation(Farm $farm, string $cropRotationId): JsonResponse
    {
        $cropRotation = $this->cropRotationService->findOneCropRotationAction($farm, $cropRotationId);

        return $this->getHttpOkResponse($cropRotation, ["cropRotation:default", "cropRotation:season", "cropRotation:field", "cropRotation:crop"]);
    }

    #[Route("/farms/{farm}/crop-rotations/{cropRotationId}", name: "farm_crop_rotations_update", methods: ["PATCH"])]
    public function updateCropRotation(Farm $farm, string $cropRotationId, #[MapRequestPayload] UpdateCropRotationDto $updateCropRotationDto): JsonResponse
    {
        $cropRotation = $this->cropRotationService->updateCropRotationAction($farm, $cropRotationId, $updateCropRotationDto);

        return $this->getHttpOkResponse($cropRotation, ["cropRotation:default", "cropRotation:season", "cropRotation:field", "cropRotation:crop"]);
    }

    #[Route("/farms/{farm}/crop-rotations/{cropRotationId}", name: "farm_crop_rotations_delete", methods: ["DELETE"])]
    public function deleteCropRotation(Farm $farm, string $cropRotationId): JsonResponse
    {
        $this->cropRotationService->deleteCropRotationAction($farm, $cropRotationId);

        return $this->getHttpNoContentResponse();
    }
}
