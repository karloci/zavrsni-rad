<?php

namespace App\Module\Timezone\Service;

use App\Entity\Country;
use App\Entity\Timezone;
use App\Module\Timezone\Dto\TimezoneDto;
use App\Module\Timezone\Repository\TimezoneRepository;
use App\Service\ServiceLocator;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TimezoneService
{
    private ServiceLocator $serviceLocator;
    private TimezoneRepository $timezoneRepository;

    public function __construct(ServiceLocator $serviceLocator, TimezoneRepository $timezoneRepository)
    {
        $this->serviceLocator = $serviceLocator;
        $this->timezoneRepository = $timezoneRepository;
    }

    /**
     * @return Timezone[]
     */
    public function findAllTimezonesAction(string $countryId): array
    {
        $timezones = $this->timezoneRepository->findAllTimezones($countryId);

        $result = [];
        foreach ($timezones as $timezone) {
            if ($this->serviceLocator->security->isGranted("READ", $timezone)) {
                $result[] = $timezone;
            }
        }

        return $result;
    }

    public function createTimezoneAction(string $countryId, TimezoneDto $timezoneDto): Timezone
    {
        if (!$this->serviceLocator->security->isGranted("ROLE_ADMIN")) {
            throw new AccessDeniedHttpException();
        }

        try {
            $timezone = new Timezone();
            $timezone->setCountry($this->serviceLocator->entityManager->getReference(Country::class, $countryId));
            $timezone->setName($timezoneDto->getName());
            $timezone->setCode($timezoneDto->getCode());

            $this->timezoneRepository->save($timezone, true);

            return $timezone;
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

    public function findOneTimezoneAction(string $countryId, string $timezoneId): Timezone
    {
        /** @var Timezone $timezone */
        $timezone = $this->timezoneRepository->findOneTimezone($timezoneId);

        if (is_null($timezone)) {
            throw new NotFoundHttpException();
        }

        if ($timezone->getCountry()->getId()->toString() !== $countryId) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("READ", $timezone)) {
            throw new AccessDeniedHttpException();
        }

        return $timezone;
    }

    public function updateTimezoneAction(string $countryId, string $timezoneId, TimezoneDto $timezoneDto): Timezone
    {
        /** @var Timezone $timezone */
        $timezone = $this->timezoneRepository->findOneTimezone($timezoneId);

        if (is_null($timezone)) {
            throw new NotFoundHttpException();
        }

        if ($timezone->getCountry()->getId()->toString() !== $countryId) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("UPDATE", $timezone)) {
            throw new AccessDeniedHttpException();
        }

        try {
            $timezone->setCountry($this->serviceLocator->entityManager->getReference(Country::class, $countryId));
            $timezone->setName($timezoneDto->getName());
            $timezone->setCode($timezoneDto->getCode());

            $this->timezoneRepository->save($timezone, true);
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

        return $timezone;
    }

    public function deleteTimezoneAction(string $countryId, string $timezoneId): void
    {
        /** @var Timezone $timezone */
        $timezone = $this->timezoneRepository->findOneTimezone($timezoneId);

        if (is_null($timezone)) {
            throw new NotFoundHttpException();
        }

        if ($timezone->getCountry()->getId()->toString() !== $countryId) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("DELETE", $timezone)) {
            throw new AccessDeniedHttpException();
        }

        if ($timezone->isDeleted()) {
            return;
        }

        try {
            $timezone->markAsDeleted();
            $this->timezoneRepository->save($timezone, true);
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }
}