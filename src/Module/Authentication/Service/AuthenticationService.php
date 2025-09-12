<?php

namespace App\Module\Authentication\Service;

use App\Entity\User;
use App\Entity\VerifyEmailToken;
use App\Module\Authentication\Dto\LoginDto;
use App\Module\Authentication\Dto\RegisterDto;
use App\Module\Authentication\Exception\UniqueUserException;
use App\Module\User\Repository\UserRepository;
use App\Module\VerifyEmail\Event\RequestEmailVerificationEvent;
use App\Module\VerifyEmail\Repository\VerifyEmailTokenRepository;
use App\Service\ServiceLocator;
use App\Service\TokenService;
use DateTimeImmutable;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use Random\RandomException;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AuthenticationService
{
    private ServiceLocator $serviceLocator;
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;
    private VerifyEmailTokenRepository $verifyEmailTokenRepository;
    private EventDispatcherInterface $eventDispatcher;
    private TokenService $tokenService;

    public function __construct(ServiceLocator $serviceLocator, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, VerifyEmailTokenRepository $verifyEmailTokenRepository, EventDispatcherInterface $eventDispatcher, TokenService $tokenService)
    {
        $this->serviceLocator = $serviceLocator;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->verifyEmailTokenRepository = $verifyEmailTokenRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->tokenService = $tokenService;
    }

    public function loginAction(LoginDto $loginDto): User
    {
        $email = $loginDto->getEmail();
        $password = $loginDto->getPassword();

        try {
            $user = $this->userRepository->findOneUserByEmail($email);

            if (is_null($user) || !$this->passwordHasher->isPasswordValid($user, $password)) {
                throw new AuthenticationException("Invalid credentials");
            }

            return $user;
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }

    public function registerAction(RegisterDto $registerDto): User
    {
        try {
            $user = new User();
            $user->setFirstName($registerDto->getFirstName());
            $user->setLastName($registerDto->getLastName());
            $user->setEmail($registerDto->getEmail());
            $user->setPassword($this->passwordHasher->hashPassword($user, $registerDto->getPassword()));

            $this->userRepository->save($user);

            $verifyEmailToken = new VerifyEmailToken();
            $verifyEmailToken->setUser($user);
            $verifyEmailToken->setToken(bin2hex(random_bytes(16)));
            $verifyEmailToken->setExpiresAt((new DateTimeImmutable())->modify("+1 hour"));

            $this->verifyEmailTokenRepository->save($verifyEmailToken, true);

            $this->eventDispatcher->dispatch(new RequestEmailVerificationEvent($user, $verifyEmailToken), RequestEmailVerificationEvent::NAME);

            return $user;
        }
        catch (UniqueConstraintViolationException) {
            throw new UniqueUserException("User with this email address already exists");
        }
        catch (RandomException $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }

    public function refreshTokenAction(Request $request): User
    {
        $token = $this->tokenService->extractRefreshTokenFromRequest($request);
        if (is_null($token)) {
            throw new AuthenticationException("Refresh token is not provided");
        }

        $userToken = $this->tokenService->decodeToken($token);
        if (is_null($userToken)) {
            throw new AuthenticationException("Invalid credentials");
        }

        try {
            $userIdentifier = $userToken->sub;

            $user = $this->userRepository->findOneUserByIdentifierAndRefreshToken($userIdentifier, $token);

            if (is_null($user)) {
                throw new AuthenticationException("Invalid credentials");
            }

            return $user;
        }
        catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }
}