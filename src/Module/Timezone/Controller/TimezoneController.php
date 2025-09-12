<?php

namespace App\Module\Timezone\Controller;

use App\Controller\ApiController;
use App\Module\Timezone\Dto\TimezoneDto;
use App\Module\Timezone\Service\TimezoneService;
use App\Serializer\DataSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class TimezoneController extends ApiController
{
    private TimezoneService $timezoneService;

    public function __construct(DataSerializer $dataSerializer, TimezoneService $timezoneService)
    {
        parent::__construct($dataSerializer);
        $this->timezoneService = $timezoneService;
    }

    #[Route("/country/{countryId}/timezones", name: "timezones_list", methods: ["GET"])]
    public function findAllTimezones(string $countryId): JsonResponse
    {
        $timezones = $this->timezoneService->findAllTimezonesAction($countryId);

        return $this->getHttpOkResponse($timezones, ["timezone:default"]);
    }

    #[Route("/country/{countryId}/timezones", name: "timezones_create", methods: ["POST"])]
    public function createTimezone(string $countryId, #[MapRequestPayload] TimezoneDto $timezoneDto): JsonResponse
    {
        $timezone = $this->timezoneService->createTimezoneAction($countryId, $timezoneDto);

        return $this->getHttpCreatedResponse($timezone, ["timezone:default"], [
            "withCountryId" => true
        ]);
    }

    #[Route("/country/{countryId}/timezones/{timezoneId}", name: "timezones_show", methods: ["GET"])]
    public function findOneTimezone(string $countryId, string $timezoneId): JsonResponse
    {
        $timezone = $this->timezoneService->findOneTimezoneAction($countryId, $timezoneId);

        return $this->getHttpOkResponse($timezone, ["timezone:default", "timezone:country"]);
    }

    #[Route("/country/{countryId}/timezones/{timezoneId}", name: "timezones_update", methods: ["PUT"])]
    public function updateTimezone(string $countryId, string $timezoneId, #[MapRequestPayload] TimezoneDto $timezoneDto): JsonResponse
    {
        $timezone = $this->timezoneService->updateTimezoneAction($countryId, $timezoneId, $timezoneDto);

        return $this->getHttpOkResponse($timezone, ["timezone:default", "timezone:country"]);
    }

    #[Route("/country/{countryId}/timezones/{timezoneId}", name: "timezones_delete", methods: ["DELETE"])]
    public function deleteTimezone(string $countryId, string $timezoneId): JsonResponse
    {
        $this->timezoneService->deleteTimezoneAction($countryId, $timezoneId);

        return $this->getHttpNoContentResponse();
    }
}
