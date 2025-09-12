<?php

namespace App\Module\Season\Service;

use App\Entity\Farm;
use App\Entity\Season;
use App\Module\Season\Dto\SeasonDto;
use App\Module\Season\Exception\UniqueSeasonException;
use App\Module\Season\Repository\SeasonRepository;
use App\Service\ServiceLocator;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SeasonService
{
    private ServiceLocator $serviceLocator;
    private SeasonRepository $seasonRepository;

    public function __construct(ServiceLocator $serviceLocator, SeasonRepository $seasonRepository)
    {
        $this->serviceLocator = $serviceLocator;
        $this->seasonRepository = $seasonRepository;
    }

    /**
     * @param Farm $farm
     *
     * @return Season[]
     */
    public function findAllSeasonsAction(Farm $farm): array
    {
        if (!$this->serviceLocator->security->isGranted("READ", $farm)) {
            throw new AccessDeniedHttpException();
        }

        $seasons = $this->seasonRepository->findAllSeasons($farm);

        $result = [];
        foreach ($seasons as $season) {
            if ($this->serviceLocator->security->isGranted("READ", $season)) {
                $result[] = $season;
            }
        }

        return $result;
    }

    public function createSeasonAction(Farm $farm, SeasonDto $seasonDto): Season
    {
        if (!$this->serviceLocator->security->isGranted("READ", $farm)) {
            throw new AccessDeniedHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("ROLE_OWNER")) {
            throw new AccessDeniedHttpException();
        }

        try {
            $season = new Season();
            $season->setFarm($farm);
            $season->setName($seasonDto->getName());
            $season->setStartDate($seasonDto->getStartDate());
            $season->setEndDate($seasonDto->getEndDate());
            $season->setCreatedBy($this->serviceLocator->security->getUser());

            $this->seasonRepository->save($season, true);

            return $season;
        }
        catch (UniqueConstraintViolationException) {
            throw new UniqueSeasonException("The season with this name already exists on farm");
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }

    public function findOneSeasonAction(Farm $farm, string $seasonId): Season
    {
        if (!$this->serviceLocator->security->isGranted("READ", $farm)) {
            throw new AccessDeniedHttpException();
        }

        $season = $this->seasonRepository->findOneSeason($seasonId);

        if (is_null($season)) {
            throw new NotFoundHttpException();
        }

        if ($season->getFarm() !== $farm) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("READ", $season)) {
            throw new AccessDeniedHttpException();
        }

        return $season;
    }

    public function updateSeasonAction(Farm $farm, string $seasonId, SeasonDto $seasonDto): Season
    {
        if (!$this->serviceLocator->security->isGranted("READ", $farm)) {
            throw new AccessDeniedHttpException();
        }

        $season = $this->seasonRepository->findOneSeason($seasonId);

        if (is_null($season)) {
            throw new NotFoundHttpException();
        }

        if ($season->getFarm() !== $farm) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("UPDATE", $season)) {
            throw new AccessDeniedHttpException();
        }

        try {
            $season->setFarm($farm);
            $season->setFarm($farm);
            $season->setName($seasonDto->getName());
            $season->setStartDate($seasonDto->getStartDate());
            $season->setEndDate($seasonDto->getEndDate());
            $season->setUpdatedBy($this->serviceLocator->security->getUser());

            $this->seasonRepository->save($season, true);

            return $season;
        }
        catch (UniqueConstraintViolationException) {
            throw new UniqueSeasonException("The season with this name already exists on farm");
        }
        catch (ORMException $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }

    public function deleteSeasonAction(Farm $farm, string $seasonId): void
    {
        if (!$this->serviceLocator->security->isGranted("READ", $farm)) {
            throw new AccessDeniedHttpException();
        }

        $season = $this->seasonRepository->findOneSeason($seasonId);

        if (is_null($season)) {
            throw new NotFoundHttpException();
        }

        if ($season->getFarm() !== $farm) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("DELETE", $season)) {
            throw new AccessDeniedHttpException();
        }

        try {
            if (!$season->isDeleted()) {
                $season->markAsDeleted($this->serviceLocator->security->getUser());
                $this->seasonRepository->save($season, true);
            }
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }
}