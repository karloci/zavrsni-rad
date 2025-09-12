<?php

namespace App\EventListener;

use App\Service\ServiceLocator;
use App\Service\TokenService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Event\LogoutEvent;

final class LogoutListener
{
    private TokenService $tokenService;
    private ServiceLocator $serviceLocator;

    public function __construct(TokenService $tokenService, ServiceLocator $serviceLocator)
    {
        $this->tokenService = $tokenService;
        $this->serviceLocator = $serviceLocator;
    }

    #[AsEventListener(event: LogoutEvent::class)]
    public function onLogout(LogoutEvent $event): void
    {
        $refreshToken = $this->tokenService->extractRefreshTokenFromRequest($event->getRequest());
        $this->tokenService->revokeRefreshToken($refreshToken);

        $response = new JsonResponse([
            "message" => "Logged out successfully",
        ]);
        $response->headers->clearCookie("accessToken");
        $response->headers->clearCookie("refreshToken");

        $event->setResponse($response);
    }
}
