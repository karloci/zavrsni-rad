<?php

namespace App\Module\ResetPassword\Controller;

use App\Controller\ApiController;
use App\Module\ResetPassword\Dto\ConfirmResetPasswordDto;
use App\Module\ResetPassword\Dto\RequestResetPasswordDto;
use App\Module\ResetPassword\Service\ResetPasswordService;
use App\Serializer\DataSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class ResetPasswordController extends ApiController
{
    private ResetPasswordService $resetPasswordService;

    public function __construct(DataSerializer $dataSerializer, ResetPasswordService $resetPasswordService)
    {
        parent::__construct($dataSerializer);
        $this->resetPasswordService = $resetPasswordService;
    }

    #[Route("/reset-password/request", name: "reset_password_request", methods: ["POST"])]
    public function requestResetPassword(#[MapRequestPayload] RequestResetPasswordDto $requestResetPasswordDto): JsonResponse
    {
        $response = $this->resetPasswordService->requestResetPasswordAction($requestResetPasswordDto);

        return $this->getHttpOkResponse($response);
    }

    #[Route("/reset-password/confirm", name: "reset_password_confirm", methods: ["POST"])]
    public function confirmResetPassword(#[MapRequestPayload] ConfirmResetPasswordDto $confirmResetPasswordDto): JsonResponse
    {
        $response = $this->resetPasswordService->confirmResetPasswordAction($confirmResetPasswordDto);

        return $this->getHttpOkResponse($response);
    }
}
