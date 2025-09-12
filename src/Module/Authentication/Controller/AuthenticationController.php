<?php

namespace App\Module\Authentication\Controller;

use App\Module\Authentication\Dto\LoginDto;
use App\Module\Authentication\Dto\RegisterDto;
use App\Module\Authentication\Service\AuthenticationService;
use App\Controller\ApiController;
use App\Serializer\DataSerializer;
use App\Service\TokenService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class AuthenticationController extends ApiController
{
    private AuthenticationService $authenticationService;
    private TokenService $tokenService;

    public function __construct(DataSerializer $dataSerializer, AuthenticationService $authenticationService, TokenService $tokenService)
    {
        parent::__construct($dataSerializer);
        $this->authenticationService = $authenticationService;
        $this->tokenService = $tokenService;
    }

    #[Route("/authentication/login", name: "authentication_login", methods: ["POST"])]
    public function login(#[MapRequestPayload] LoginDto $loginDto): JsonResponse
    {
        $user = $this->authenticationService->loginAction($loginDto);

        return $this->tokenService->provideAuthenticationResponse($user);
    }

    #[Route("/authentication/register", name: "authentication_register", methods: ["POST"])]
    public function register(#[MapRequestPayload] RegisterDto $registerDto): JsonResponse
    {
        $user = $this->authenticationService->registerAction($registerDto);

        return $this->tokenService->provideAuthenticationResponse($user);
    }

    #[Route("/authentication/refresh-token", name: "authentication_refresh_token", methods: ["POST"])]
    public function refreshToken(Request $request): JsonResponse
    {
        $user = $this->authenticationService->refreshTokenAction($request);

        return $this->tokenService->provideAuthenticationResponse($user);
    }
}