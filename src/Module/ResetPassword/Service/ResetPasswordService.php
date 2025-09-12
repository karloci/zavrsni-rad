<?php

namespace App\Module\ResetPassword\Service;

use App\Entity\ResetPasswordToken;
use App\Entity\User;
use App\Module\ResetPassword\Dto\ConfirmResetPasswordDto;
use App\Module\ResetPassword\Dto\RequestResetPasswordDto;
use App\Module\ResetPassword\Event\RequestResetPasswordEvent;
use App\Module\ResetPassword\Repository\ResetPasswordTokenRepository;
use App\Module\User\Repository\UserRepository;
use App\Service\ServiceLocator;
use DateTimeImmutable;
use Exception;
use Random\RandomException;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordService
{
    private UserRepository $userRepository;
    private ResetPasswordTOkenRepository $resetPasswordTokenRepository;
    private EventDispatcherInterface $eventDispatcher;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserRepository $userRepository, ResetPasswordTOkenRepository $resetPasswordTokenRepository, EventDispatcherInterface $eventDispatcher, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->resetPasswordTokenRepository = $resetPasswordTokenRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->passwordHasher = $passwordHasher;
    }

    public function requestResetPasswordAction(RequestResetPasswordDto $requestResetPasswordDto): string
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy([
            "email" => $requestResetPasswordDto->getEmail()
        ]);

        if (!is_null($user)) {
            try {
                $resetPasswordToken = new ResetPasswordToken();
                $resetPasswordToken->setUser($user);
                $resetPasswordToken->setToken(bin2hex(random_bytes(16)));
                $resetPasswordToken->setExpiresAt((new DateTimeImmutable())->modify("+1 hour"));

                $this->resetPasswordTokenRepository->save($resetPasswordToken, true);

                $this->eventDispatcher->dispatch(new RequestResetPasswordEvent($user, $resetPasswordToken));
            }
            catch (RandomException $e) {
                throw new RuntimeException($e->getMessage(), 0, $e);
            }
            catch (Exception $e) {
                throw new BadRequestHttpException($e->getMessage(), $e);
            }
        }

        return "Email for password reset has been successfully sent";
    }

    public function confirmResetPasswordAction(ConfirmResetPasswordDto $confirmResetPasswordDto): string
    {
        /** @var User $user */
        $user = $this->userRepository->findOneUserByResetPasswordToken($confirmResetPasswordDto->getToken());

        if (is_null($user)) {
            throw new BadRequestHttpException();
        }

        $isConfirmed = false;
        foreach ($user->getResetPasswordTokens() as $resetPasswordToken) {
            if ($resetPasswordToken->getToken() === $confirmResetPasswordDto->getToken()) {
                try {
                    $user->setPassword($this->passwordHasher->hashPassword($user, $confirmResetPasswordDto->getPassword()));
                    $user->removeResetPasswordToken($resetPasswordToken);

                    $this->userRepository->save($user, true);
                }
                catch (Exception $e) {
                    throw new BadRequestHttpException($e->getMessage(), $e);
                }

                $isConfirmed = true;
                break;
            }
        }

        if (!$isConfirmed) {
            throw new BadRequestHttpException();
        }

        return "The password has been successfully reset";
    }
}