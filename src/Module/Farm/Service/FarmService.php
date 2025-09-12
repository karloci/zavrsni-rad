<?php

namespace App\Module\Farm\Service;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\Farm;
use App\Entity\Timezone;
use App\Entity\User;
use App\Module\Farm\Dto\CreateFarmDto;
use App\Module\Farm\Dto\UpdateFarmDto;
use App\Module\Farm\Exception\UserHasFarmException;
use App\Module\Farm\Repository\FarmRepository;
use App\Module\User\Repository\UserRepository;
use App\Service\ServiceLocator;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class FarmService
{
    private ServiceLocator $serviceLocator;
    private FarmRepository $farmRepository;
    private UserRepository $userRepository;

    public function __construct(ServiceLocator $serviceLocator, FarmRepository $farmRepository, UserRepository $userRepository)
    {
        $this->serviceLocator = $serviceLocator;
        $this->farmRepository = $farmRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @return Farm[]
     */
    public function findAllFarmsAction(): array
    {
        if ($this->serviceLocator->security->isGranted("ROLE_ADMIN")) {
            $farms = $this->farmRepository->findAllFarms();
        }
        else {
            /** @var User $user */
            $user = $this->serviceLocator->security->getUser();
            $farms = $this->farmRepository->findAllFarmsByUser($user);
        }

        $result = [];
        foreach ($farms as $farm) {
            if ($this->serviceLocator->security->isGranted("READ", $farm)) {
                $result[] = $farm;
            }
        }

        return $result;
    }

    public function createFarmAction(CreateFarmDto $farmDto): Farm
    {
        if ($this->serviceLocator->security->isGranted("ROLE_ADMIN")) {
            /** @var User $user */
            $user = $this->userRepository->findOneUser($farmDto->getOwner()->toString());

            if (is_null($user)) {
                throw new UnprocessableEntityHttpException();
            }
        }
        else {
            /** @var User $user */
            $user = $this->serviceLocator->security->getUser();

            if ($farmDto->getOwner()->toString() !== $user->getId()->toString()) {
                throw new AccessDeniedHttpException();
            }
        }

        if (!is_null($user->getFarm())) {
            throw new UserHasFarmException("The user already has a farm linked to their account");
        }

        try {
            $farm = new Farm();
            $farm->setName($farmDto->getName());
            $farm->setCountry($this->serviceLocator->entityManager->getReference(Country::class, $farmDto->getCountry()));
            $farm->setCity($this->serviceLocator->entityManager->getReference(City::class, $farmDto->getCity()));
            $farm->setTimezone($this->serviceLocator->entityManager->getReference(Timezone::class, $farmDto->getTimezone()));
            $farm->setAddress($farmDto->getAddress());
            $farm->setPhone($farmDto->getPhone());
            $farm->setEmail($farmDto->getEmail());
            $farm->setWebsite($farmDto->getWebsite());
            $farm->setCreatedBy($this->serviceLocator->security->getUser());

            $this->farmRepository->save($farm);

            $user->setFarm($farm);
            $user->addRole("ROLE_OWNER");
            $this->userRepository->save($user, true);

            return $farm;
        }
        catch (ORMException $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }

    public function findOneFarmAction(string $farmId): Farm
    {
        $farm = $this->farmRepository->findOneFarm($farmId);

        if (is_null($farm)) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("READ", $farm)) {
            throw new AccessDeniedHttpException();
        }

        return $farm;
    }

    public function updateFarmAction(string $farmId, UpdateFarmDto $farmDto): Farm
    {
        /** @var Farm $farm */
        $farm = $this->farmRepository->findOneFarm($farmId);

        if (is_null($farm)) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("UPDATE", $farm)) {
            throw new AccessDeniedHttpException();
        }

        try {
            $farm->setName($farmDto->getName());
            $farm->setCountry($this->serviceLocator->entityManager->getReference(Country::class, $farmDto->getCountry()));
            $farm->setCity($this->serviceLocator->entityManager->getReference(City::class, $farmDto->getCity()));
            $farm->setTimezone($this->serviceLocator->entityManager->getReference(Timezone::class, $farmDto->getTimezone()));
            $farm->setAddress($farmDto->getAddress());
            $farm->setPhone($farmDto->getPhone());
            $farm->setEmail($farmDto->getEmail());
            $farm->setWebsite($farmDto->getWebsite());
            $farm->setUpdatedBy($this->serviceLocator->security->getUser());

            $this->farmRepository->save($farm, TRUE);

            return $farm;
        }
        catch (ORMException $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }

    public function deleteFarmAction(string $farmId): void
    {
        $farm = $this->farmRepository->findOneFarm($farmId);

        if (is_null($farm)) {
            throw new NotFoundHttpException();
        }

        if (!$this->serviceLocator->security->isGranted("DELETE", $farm)) {
            throw new AccessDeniedHttpException();
        }

        try {
            if (!$farm->isDeleted()) {
                $farm->markAsDeleted($this->serviceLocator->security->getUser());
                $this->farmRepository->save($farm, true);
            }
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }
}