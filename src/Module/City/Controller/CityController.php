<?php

namespace App\Module\City\Controller;

use App\Module\City\Dto\CityDto;
use App\Controller\ApiController;
use App\Module\City\Service\CityService;
use App\Serializer\DataSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class CityController extends ApiController
{
    private CityService $cityService;
    
    public function __construct(DataSerializer $dataSerializer, CityService $cityService)
    {
        parent::__construct($dataSerializer);
        $this->cityService = $cityService;
    }


    #[Route("/country/{countryId}/cities", name: "country_cities_list", methods: ["GET"])]
    public function findAllCities(string $countryId, ): JsonResponse
    {
        $cities = $this->cityService->findAllCitiesAction($countryId);

        return $this->getHttpOkResponse($cities, ["city:default"]);
    }

    #[Route("/country/{countryId}/cities", name: "country_cities_create", methods: ["POST"])]
    public function createCity(string $countryId, #[MapRequestPayload] CityDto $cityDto): JsonResponse
    {
        $city = $this->cityService->createCityAction($countryId, $cityDto);

        return $this->getHttpCreatedResponse($city, ["city:default"], [
            "withCountryId" => true
        ]);
    }

    #[Route("/country/{countryId}/cities/{cityId}", name: "country_cities_show", methods: ["GET"])]
    public function findOneCity(string $countryId, string $cityId): JsonResponse
    {
        $city = $this->cityService->findOneCityAction($countryId, $cityId);

        return $this->getHttpOkResponse($city, ["city:default"]);
    }

    #[Route("/country/{countryId}/cities/{cityId}", name: "country_cities_update", methods: ["PUT"])]
    public function updateCity(string $countryId, string $cityId, #[MapRequestPayload] CityDto $cityDto): JsonResponse
    {
        $city = $this->cityService->updateCityAction($countryId, $cityId, $cityDto);

        return $this->getHttpOkResponse($city, ["city:default"]);
    }

    #[Route("/country/{countryId}/cities/{cityId}", name: "country_cities_delete", methods: ["DELETE"])]
    public function deleteCity(string $countryId, string $cityId): JsonResponse
    {
        $this->cityService->deleteCityAction($countryId, $cityId);

        return $this->getHttpNoContentResponse();
    }
}
