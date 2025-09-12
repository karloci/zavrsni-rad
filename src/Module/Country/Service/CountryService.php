<?php

namespace App\Module\Country\Service;

use App\Entity\Country;
use App\Module\Country\Dto\CountryDto;
use App\Module\Country\Repository\CountryRepository;
use App\Service\ServiceLocator;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CountryService
{
    private ServiceLocator $serviceLocator;
    private CountryRepository $countryRepository;

    public function __construct(ServiceLocator $serviceLocator, CountryRepository $countryRepository)
    {
        $this->serviceLocator = $serviceLocator;
        $this->countryRepository = $countryRepository;
    }

    /**
     * @return Country[]
     */
    public function findAllCountriesAction(): array
    {
        $countries = $this->countryRepository->findAllCountries();

        $result = [];
        foreach ($countries as $country) {
            if ($this->serviceLocator->security->isGranted("READ", $country)) {
                $result[] = $country;
            }
        }

        return $result;
    }

    public function createCountryAction(CountryDto $countryDto): Country
    {
        if (!$this->serviceLocator->security->isGranted("ROLE_ADMIN")) {
            throw new AccessDeniedHttpException();
        }

        try {
            $country = new Country();
            $country->setName($countryDto->getName());
            $country->setCode(mb_strtoupper($countryDto->getCode()));

            $this->countryRepository->save($country, true);

            return $country;
        }
        catch (UniqueConstraintViolationException) {
            throw new ConflictHttpException();
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }

    public function findOneCountryAction(string $countryId): Country
    {
        $country = $this->countryRepository->findOneCountry($countryId);

        if (is_null($country)) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("READ", $country)) {
            throw new AccessDeniedHttpException();
        }

        return $country;
    }

    public function updateCountryAction(string $countryId, CountryDto $countryDto): Country
    {
        $country = $this->countryRepository->findOneCountry($countryId);

        if (is_null($country)) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("UPDATE", $country)) {
            throw new AccessDeniedHttpException();
        }

        try {
            $country->setName($countryDto->getName());
            $country->setCode(mb_strtoupper($countryDto->getCode()));

            $this->countryRepository->save($country, true);
        }
        catch (UniqueConstraintViolationException) {
            throw new ConflictHttpException();
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        return $country;
    }

    public function deleteCountryAction(string $countryId): void
    {
        $country = $this->countryRepository->findOneCountry($countryId);

        if (is_null($country)) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("DELETE", $country)) {
            throw new AccessDeniedHttpException();
        }

        if ($country->isDeleted()) {
            return;
        }

        try {
            $country->markAsDeleted();
            $this->countryRepository->save($country, true);
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }
}