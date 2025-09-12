<?php

namespace App\Module\Farm\Controller;

use App\Controller\ApiController;
use App\Module\Farm\Dto\CreateFarmDto;
use App\Module\Farm\Dto\UpdateFarmDto;
use App\Module\Farm\Service\FarmService;
use App\Serializer\DataSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class FarmController extends ApiController
{
    private FarmService $farmService;

    public function __construct(DataSerializer $dataSerializer, FarmService $farmService)
    {
        parent::__construct($dataSerializer);
        $this->farmService = $farmService;
    }

    #[Route("/farms", name: "farms_list", methods: ["GET"])]
    public function findAllFarms(): JsonResponse
    {
        $farms = $this->farmService->findAllFarmsAction();

        return $this->getHttpOkResponse($farms, ["farm:default"]);
    }

    #[Route("/farms", name: "farms_create", methods: ["POST"])]
    public function createFarm(#[MapRequestPayload] CreateFarmDto $createFarmDto): JsonResponse
    {
        $farm = $this->farmService->createFarmAction($createFarmDto);

        return $this->getHttpCreatedResponse($farm, ["farm:default", "farm:country", "farm:city", "farm:timezone"]);
    }

    #[Route("/farms/{farmId}", name: "farms_show", methods: ["GET"])]
    public function findOneFarm(string $farmId): JsonResponse
    {
        $farm = $this->farmService->findOneFarmAction($farmId);

        return $this->getHttpOkResponse($farm, ["farm:default", "farm:country", "farm:city", "farm:timezone", "farm:fields"]);
    }

    #[Route("/farms/{farmId}", name: "farms_update", methods: ["PATCH"])]
    public function updateFarm(string $farmId, #[MapRequestPayload] UpdateFarmDto $updateFarmDto): JsonResponse
    {
        $farm = $this->farmService->updateFarmAction($farmId, $updateFarmDto);

        return $this->getHttpOkResponse($farm, ["farm:default", "farm:country", "farm:city", "farm:timezone", "farm:fields"]);
    }

    #[Route("/farms/{farmId}", name: "farms_delete", methods: ["DELETE"])]
    public function deleteFarm(string $farmId): JsonResponse
    {
        $this->farmService->deleteFarmAction($farmId);

        return $this->getHttpNoContentResponse();
    }
}
