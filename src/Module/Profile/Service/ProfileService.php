<?php

namespace App\Module\Profile\Service;

use App\Entity\User;
use App\Entity\VerifyEmailToken;
use App\Module\Profile\Dto\ChangeEmailDto;
use App\Module\Profile\Dto\ChangePasswordDto;
use App\Module\Profile\Dto\UpdateProfileDto;
use App\Module\User\Repository\UserRepository;
use App\Module\VerifyEmail\Event\RequestEmailVerificationEvent;
use App\Module\VerifyEmail\Repository\VerifyEmailTokenRepository;
use App\Service\ServiceLocator;
use DateTimeImmutable;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileService
{
    private ServiceLocator $serviceLocator;
    private UserRepository $userRepository;
    private VerifyEmailTokenRepository $verifyEmailTokenRepository;
    private EventDispatcherInterface $eventDispatcher;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(ServiceLocator $serviceLocator, UserRepository $userRepository, VerifyEmailTokenRepository $verifyEmailTokenRepository, EventDispatcherInterface $eventDispatcher, UserPasswordHasherInterface $passwordHasher)
    {
        $this->serviceLocator = $serviceLocator;
        $this->userRepository = $userRepository;
        $this->verifyEmailTokenRepository = $verifyEmailTokenRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->passwordHasher = $passwordHasher;
    }

    public function updateProfileAction(UpdateProfileDto $updateProfileDto): User
    {
        /** @var User $user */
        $user = $this->serviceLocator->security->getUser();

        try {
            $user->setFirstName($updateProfileDto->getFirstName());
            $user->setLastName($updateProfileDto->getLastName());

            $this->userRepository->save($user, true);

            return $user;
        }
        catch (UniqueConstraintViolationException $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }

    public function changeEmailAction(ChangeEmailDto $changeEmailDto): void
    {
        /** @var User $user */
        $user = $this->serviceLocator->security->getUser();

        if (strcmp($user->getEmail(), $changeEmailDto->getEmail()) !== 0) {
            try {
                $user->setEmail($changeEmailDto->getEmail());
                $user->setEmailVerifiedAt(null);

                $this->userRepository->save($user);

                $verifyEmailToken = new VerifyEmailToken();
                $verifyEmailToken->setUser($user);
                $verifyEmailToken->setToken(bin2hex(random_bytes(16)));
                $verifyEmailToken->setExpiresAt((new DateTimeImmutable())->modify("+1 hour"));
                $this->verifyEmailTokenRepository->save($verifyEmailToken, true);

                $this->eventDispatcher->dispatch(new RequestEmailVerificationEvent($user, $verifyEmailToken), RequestEmailVerificationEvent::NAME);
            }
            catch (Exception $e) {
                throw new BadRequestHttpException($e->getMessage(), $e);
            }
        }
    }

    public function changePasswordAction(ChangePasswordDto $changePasswordDto): void
    {
        /** @var User $user */
        $user = $this->serviceLocator->security->getUser();

        try {
            $user->setPassword($this->passwordHasher->hashPassword($user, $changePasswordDto->getNewPassword()));

            $this->userRepository->save($user, true);
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }
}