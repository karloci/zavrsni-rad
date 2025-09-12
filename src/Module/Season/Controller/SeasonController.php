<?php

namespace App\Module\Season\Controller;

use App\Controller\ApiController;
use App\Entity\Farm;
use App\Module\Season\Dto\SeasonDto;
use App\Module\Season\Service\SeasonService;
use App\Serializer\DataSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class SeasonController extends ApiController
{
    private SeasonService $seasonService;

    public function __construct(DataSerializer $dataSerializer, SeasonService $seasonService)
    {
        parent::__construct($dataSerializer);
        $this->seasonService = $seasonService;
    }

    #[Route("/farms/{farm}/seasons", name: "farm_seasons_list", methods: ["GET"])]
    public function findAllSeasons(Farm $farm): JsonResponse
    {
        $seasons = $this->seasonService->findAllSeasonsAction($farm);

        return $this->getHttpOkResponse($seasons, ["season:default"]);
    }

    #[Route("/farms/{farm}/seasons", name: "farm_seasons_create", methods: ["POST"])]
    public function createSeason(Farm $farm, #[MapRequestPayload] SeasonDto $seasonDto): JsonResponse
    {
        $season = $this->seasonService->createSeasonAction($farm, $seasonDto);

        return $this->getHttpCreatedResponse($season, ["season:default"]);
    }

    #[Route("/farms/{farm}/seasons/{seasonId}", name: "farm_seasons_show", methods: ["GET"])]
    public function findOneSeason(Farm $farm, string $seasonId): JsonResponse
    {
        $season = $this->seasonService->findOneSeasonAction($farm, $seasonId);

        return $this->getHttpOkResponse($season, ["season:default"]);
    }

    #[Route("/farms/{farm}/seasons/{seasonId}", name: "farm_seasons_update", methods: ["PUT"])]
    public function updateSeason(Farm $farm, string $seasonId, #[MapRequestPayload] SeasonDto $seasonDto): JsonResponse
    {
        $season = $this->seasonService->updateSeasonAction($farm, $seasonId, $seasonDto);

        return $this->getHttpOkResponse($season, ["season:default", "season:seasonType", "season:soilType"]);
    }

    #[Route("/farms/{farm}/seasons/{seasonId}", name: "farm_seasons_delete", methods: ["DELETE"])]
    public function deleteSeason(Farm $farm, string $seasonId): JsonResponse
    {
        $this->seasonService->deleteSeasonAction($farm, $seasonId);

        return $this->getHttpNoContentResponse();
    }
}