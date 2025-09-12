<?php

namespace App\Module\City\Service;

use App\Entity\City;
use App\Entity\Country;
use App\Module\City\Dto\CityDto;
use App\Module\City\Repository\CityRepository;
use App\Service\ServiceLocator;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CityService
{
    private ServiceLocator $serviceLocator;
    private CityRepository $cityRepository;

    public function __construct(ServiceLocator $serviceLocator, CityRepository $cityRepository)
    {
        $this->serviceLocator = $serviceLocator;
        $this->cityRepository = $cityRepository;
    }

    /**
     * @param string $countryId
     *
     * @return City[]
     */
    public function findAllCitiesAction(string $countryId): array
    {
        $cities = $this->cityRepository->findAllCities($countryId);

        $result = [];
        foreach ($cities as $city) {
            if ($this->serviceLocator->security->isGranted("READ", $city)) {
                $result[] = $city;
            }
        }

        return $result;
    }

    public function createCityAction(string $countryId, CityDto $cityDto): City
    {
        if (!$this->serviceLocator->security->isGranted("ROLE_ADMIN")) {
            throw new AccessDeniedHttpException();
        }

        try {
            $city = new City();
            $city->setCountry($this->serviceLocator->entityManager->getReference(Country::class, $countryId));
            $city->setName($cityDto->getName());
            $city->setLongitude($cityDto->getLongitude());
            $city->setLatitude($cityDto->getLatitude());

            $this->cityRepository->save($city, true);

            return $city;
        }
        catch (UniqueConstraintViolationException) {
            throw new ConflictHttpException();
        }
        catch (ORMException $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }

    public function findOneCityAction(string $countryId, string $cityId): City
    {
        $city = $this->cityRepository->findOneCity($cityId);

        if (is_null($city)) {
            throw new NotFoundHttpException();
        }

        if ($city->getCountry()->getId()->toString() !== $countryId) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("READ", $city)) {
            throw new AccessDeniedHttpException();
        }

        return $city;
    }

    public function updateCityAction(string $countryId, string $cityId, CityDto $cityDto): City
    {
        $city = $this->cityRepository->findOneCity($cityId);

        if (is_null($city)) {
            throw new NotFoundHttpException();
        }

        if ($city->getCountry()->getId()->toString() !== $countryId) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("UPDATE", $city)) {
            throw new AccessDeniedHttpException();
        }

        try {
            $city->setCountry($this->serviceLocator->entityManager->getReference(Country::class, $countryId));
            $city->setName($cityDto->getName());
            $city->setLongitude($cityDto->getLongitude());
            $city->setLatitude($cityDto->getLatitude());

            $this->cityRepository->save($city, true);
        }
        catch (UniqueConstraintViolationException) {
            throw new ConflictHttpException();
        }
        catch (ORMException $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        return $city;
    }

    public function deleteCityAction(string $countryId, string $cityId): void
    {
        $city = $this->cityRepository->findOneCity($cityId);

        if (is_null($city)) {
            throw new NotFoundHttpException();
        }

        if ($city->getCountry()->getId()->toString() !== $countryId) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("DELETE", $city)) {
            throw new AccessDeniedHttpException();
        }

        if ($city->isDeleted()) {
            return;
        }

        try {
            $city->markAsDeleted();
            $this->cityRepository->save($city, true);
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }
}
