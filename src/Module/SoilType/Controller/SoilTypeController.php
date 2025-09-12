<?php

namespace App\Module\SoilType\Controller;

use App\Controller\ApiController;
use App\Module\SoilType\Dto\SoilTypeDto;
use App\Module\SoilType\Service\SoilTypeService;
use App\Serializer\DataSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class SoilTypeController extends ApiController
{
    private SoilTypeService $soilTypeService;

    public function __construct(DataSerializer $dataSerializer, SoilTypeService $soilTypeService)
    {
        parent::__construct($dataSerializer);
        $this->soilTypeService = $soilTypeService;
    }

    #[Route("/soil-types", name: "soil_types_list", methods: ["GET"])]
    public function findAllSoilTypes(): JsonResponse
    {
        $soilTypes = $this->soilTypeService->findAllSoilTypesAction();

        return $this->getHttpOkResponse($soilTypes, ["soilType:default"]);
    }

    #[Route("/soil-types", name: "soil_types_create", methods: ["POST"])]
    public function createSoilType(#[MapRequestPayload] SoilTypeDto $soilTypeDto): JsonResponse
    {
        $soilType = $this->soilTypeService->createSoilTypeAction($soilTypeDto);

        return $this->getHttpCreatedResponse($soilType, ["soilType:default"]);
    }

    #[Route("/soil-types/{soilTypeId}", name: "soil_types_show", methods: ["GET"])]
    public function findOneSoilType(string $soilTypeId): JsonResponse
    {
        $soilType = $this->soilTypeService->findOneSoilTypeAction($soilTypeId);

        return $this->getHttpOkResponse($soilType, ["soilType:default"]);
    }

    #[Route("/soil-types/{soilTypeId}", name: "soil_types_update", methods: ["PUT"])]
    public function updateSoilType(string $soilTypeId, #[MapRequestPayload] SoilTypeDto $soilTypeDto): JsonResponse
    {
        $soilType = $this->soilTypeService->updateSoilTypeAction($soilTypeId, $soilTypeDto);

        return $this->getHttpOkResponse($soilType, ["soilType:default"]);
    }

    #[Route("/soil-types/{soilTypeId}", name: "soil_types_delete", methods: ["DELETE"])]
    public function deleteSoilType(string $soilTypeId): JsonResponse
    {
        $this->soilTypeService->deleteSoilTypeAction($soilTypeId);

        return $this->getHttpNoContentResponse();
    }
}
