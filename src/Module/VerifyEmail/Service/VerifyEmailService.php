<?php

namespace App\Module\VerifyEmail\Service;

use App\Entity\User;
use App\Entity\VerifyEmailToken;
use App\Module\User\Repository\UserRepository;
use App\Module\VerifyEmail\Dto\ConfirmVerifyEmailDto;
use App\Module\VerifyEmail\Dto\RequestVerifyEmailDto;
use App\Module\VerifyEmail\Event\RequestEmailVerificationEvent;
use App\Module\VerifyEmail\Repository\VerifyEmailTokenRepository;
use App\Service\ServiceLocator;
use DateTimeImmutable;
use Exception;
use Random\RandomException;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class VerifyEmailService
{
    private UserRepository $userRepository;
    private VerifyEmailTokenRepository $verifyEmailTokenRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(UserRepository $userRepository, VerifyEmailTokenRepository $verifyEmailTokenRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->userRepository = $userRepository;
        $this->verifyEmailTokenRepository = $verifyEmailTokenRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function requestVerifyEmailAction(RequestVerifyEmailDto $requestVerifyEmailDto): string
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy([
            "email" => $requestVerifyEmailDto->getEmail()
        ]);

        if (!is_null($user) && is_null($user->getEmailVerifiedAt())) {
            try {
                $verifyEmailToken = new VerifyEmailToken();
                $verifyEmailToken->setUser($user);
                $verifyEmailToken->setToken(bin2hex(random_bytes(16)));
                $verifyEmailToken->setExpiresAt((new DateTimeImmutable())->modify("+1 hour"));

                $this->verifyEmailTokenRepository->save($verifyEmailToken, true);

                $this->eventDispatcher->dispatch(new RequestEmailVerificationEvent($user, $verifyEmailToken), RequestEmailVerificationEvent::NAME);
            }
            catch (RandomException $e) {
                throw new RuntimeException($e->getMessage(), 0, $e);
            }
            catch (Exception $e) {
                throw new BadRequestHttpException($e->getMessage(), $e);
            }
        }

        return "Email for verification has been successfully sent";
    }

    public function confirmVerifyEmailAction(ConfirmVerifyEmailDto $confirmVerifyEmailDto): string
    {
        /** @var User $user */
        $user = $this->userRepository->findOneUserByVerifyEmailToken($confirmVerifyEmailDto->getToken());

        if (is_null($user)) {
            throw new BadRequestHttpException();
        }

        $isConfirmed = false;
        foreach ($user->getVerifyEmailTokens() as $verifyEmailToken) {
            if ($verifyEmailToken->getToken() === $confirmVerifyEmailDto->getToken()) {
                try {
                    $user->setEmailVerifiedAt(new DateTimeImmutable());
                    $user->removeVerifyEmailToken($verifyEmailToken);

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

        return "The email address has been successfully verified";
    }
}