<?php

namespace App\Module\Country\Controller;

use App\Controller\ApiController;
use App\Module\Country\Dto\CountryDto;
use App\Module\Country\Service\CountryService;
use App\Serializer\DataSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class CountryController extends ApiController
{
    private CountryService $countryService;

    public function __construct(DataSerializer $dataSerializer, CountryService $countryService)
    {
        parent::__construct($dataSerializer);
        $this->countryService = $countryService;
    }

    #[Route("/countries", name: "countries_list", methods: ["GET"])]
    public function findAllCountries(): JsonResponse
    {
        $countries = $this->countryService->findAllCountriesAction();

        return $this->getHttpOkResponse($countries, ["country:default"]);
    }

    #[Route("/countries", name: "countries_create", methods: ["POST"])]
    public function createCountry(#[MapRequestPayload] CountryDto $countryDto): JsonResponse
    {
        $country = $this->countryService->createCountryAction($countryDto);

        return $this->getHttpCreatedResponse($country, ["country:default"]);
    }

    #[Route("/countries/{countryId}", name: "countries_show", methods: ["GET"])]
    public function findOneCountry(string $countryId): JsonResponse
    {
        $country = $this->countryService->findOneCountryAction($countryId);

        return $this->getHttpOkResponse($country, ["country:default", "country:cities", "country:timezones"]);
    }

    #[Route("/countries/{countryId}", name: "countries_update", methods: ["PUT"])]
    public function updateCountry(string $countryId, #[MapRequestPayload] CountryDto $countryDto): JsonResponse
    {
        $country = $this->countryService->updateCountryAction($countryId, $countryDto);

        return $this->getHttpOkResponse($country, ["country:default", "country:cities", "country:timezones"]);
    }

    #[Route("/countries/{countryId}", name: "countries_delete", methods: ["DELETE"])]
    public function deleteCountry(string $countryId): JsonResponse
    {
        $this->countryService->deleteCountryAction($countryId);

        return $this->getHttpNoContentResponse();
    }
}
