<?php

namespace App\Module\VerifyEmail\Controller;

use App\Controller\ApiController;
use App\Module\VerifyEmail\Dto\ConfirmVerifyEmailDto;
use App\Module\VerifyEmail\Dto\RequestVerifyEmailDto;
use App\Module\VerifyEmail\Service\VerifyEmailService;
use App\Serializer\DataSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class VerifyEmailController extends ApiController
{
    private VerifyEmailService $verifyEmailService;

    public function __construct(DataSerializer $dataSerializer, VerifyEmailService $verifyEmailService)
    {
        parent::__construct($dataSerializer);
        $this->verifyEmailService = $verifyEmailService;
    }

    #[Route("/verify-email/request", name: "verify_email_request", methods: ["POST"])]
    public function requestVerifyEmail(#[MapRequestPayload] RequestVerifyEmailDto $requestVerifyEmailDto): JsonResponse
    {
        $response = $this->verifyEmailService->requestVerifyEmailAction($requestVerifyEmailDto);

        return $this->getHttpOkResponse($response);
    }

    #[Route("/verify-email/confirm", name: "verify_email_confirm", methods: ["POST"])]
    public function confirmVerifyEmail(#[MapRequestPayload] ConfirmVerifyEmailDto $confirmVerifyEmailDto): JsonResponse
    {
        $response = $this->verifyEmailService->confirmVerifyEmailAction($confirmVerifyEmailDto);

        return $this->getHttpOkResponse($response);
    }
}
