<?php

namespace App\Module\Profile\Controller;

use App\Controller\ApiController;
use App\Module\Profile\Dto\ChangeEmailDto;
use App\Module\Profile\Dto\ChangePasswordDto;
use App\Module\Profile\Dto\UpdateProfileDto;
use App\Module\Profile\Service\ProfileService;
use App\Serializer\DataSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends ApiController
{
    private ProfileService $profileService;

    public function __construct(DataSerializer $dataSerializer, ProfileService $profileService)
    {
        parent::__construct($dataSerializer);
        $this->profileService = $profileService;
    }

    #[Route("/profile", name: "profile_update", methods: ["PATCH"])]
    public function updateProfile(#[MapRequestPayload] UpdateProfileDto $updateProfileDto): JsonResponse
    {
        $user = $this->profileService->updateProfileAction($updateProfileDto);

        return $this->getHttpOkResponse($user, ["user:default"]);
    }

    #[Route("/profile/email", name: "profile_email_change", methods: ["PATCH"])]
    public function changeEmail(#[MapRequestPayload] ChangeEmailDto $changeEmailDto): JsonResponse
    {
        $this->profileService->changeEmailAction($changeEmailDto);

        return $this->getHttpOkResponse("Email for verification has been successfully sent");
    }

    #[Route("/profile/password", name: "profile_password_change", methods: ["PATCH"])]
    public function changePassword(#[MapRequestPayload] ChangePasswordDto $changePasswordDto): JsonResponse
    {
        $this->profileService->changePasswordAction($changePasswordDto);

        return $this->getHttpOkResponse("The password has been successfully changed");
    }
}