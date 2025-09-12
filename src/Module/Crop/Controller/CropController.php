<?php

namespace App\Module\Crop\Controller;

use App\Controller\ApiController;
use App\Module\Crop\Dto\CropDto;
use App\Module\Crop\Service\CropService;
use App\Serializer\DataSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class CropController extends ApiController
{
    private CropService $cropService;

    public function __construct(DataSerializer $dataSerializer, CropService $cropService)
    {
        parent::__construct($dataSerializer);
        $this->cropService = $cropService;
    }

    #[Route("/crops", name: "crops_list", methods: ["GET"])]
    public function findAllCrops(): JsonResponse
    {
        $crops = $this->cropService->findAllCropsAction();

        return $this->getHttpOkResponse($crops, ["crop:default"]);
    }

    #[Route("/crops", name: "crops_create", methods: ["POST"])]
    public function createCrop(#[MapRequestPayload] CropDto $cropDto): JsonResponse
    {
        $crop = $this->cropService->createCropAction($cropDto);

        return $this->getHttpCreatedResponse($crop, ["crop:default"]);
    }

    #[Route("/crops/{cropId}", name: "crops_show", methods: ["GET"])]
    public function findOneCrop(string $cropId): JsonResponse
    {
        $crop = $this->cropService->findOneCropAction($cropId);

        return $this->getHttpOkResponse($crop, ["crop:default"]);
    }

    #[Route("/crops/{cropId}", name: "crops_update", methods: ["PUT"])]
    public function updateCrop(string $cropId, #[MapRequestPayload] CropDto $cropDto): JsonResponse
    {
        $crop = $this->cropService->updateCropAction($cropId, $cropDto);

        return $this->getHttpOkResponse($crop, ["crop:default"]);
    }

    #[Route("/crops/{cropId}", name: "crops_delete", methods: ["DELETE"])]
    public function deleteCrop(string $cropId): JsonResponse
    {
        $this->cropService->deleteCropAction($cropId);

        return $this->getHttpNoContentResponse();
    }
}
