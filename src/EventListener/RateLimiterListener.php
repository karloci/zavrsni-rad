<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\RateLimiter\RateLimiterFactory;

final class RateLimiterListener
{
    private RateLimiterFactory $rateLimiterFactory;

    public function __construct(RateLimiterFactory $authenticatedApiLimiter)
    {
        $this->rateLimiterFactory = $authenticatedApiLimiter;
    }

    #[AsEventListener(event: KernelEvents::REQUEST)]
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $limiter = $this->rateLimiterFactory->create($event->getRequest()->getClientIp());
        $limit = $limiter->consume(1);

        if (!$limit->isAccepted()) {
            $event->setResponse(new JsonResponse([
                "message" => "Too many requests, please try again later",
            ], Response::HTTP_TOO_MANY_REQUESTS));
        }
    }
}
