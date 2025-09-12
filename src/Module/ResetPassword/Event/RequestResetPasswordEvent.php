<?php

namespace App\Module\ResetPassword\Event;

use App\Entity\ResetPasswordToken;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class RequestResetPasswordEvent extends Event
{
    public const string NAME = "user.request_reset_password";

    private User $user;

    private ResetPasswordToken $resetPasswordToken;

    public function __construct(User $user, ResetPasswordToken $resetPasswordToken)
    {
        $this->user = $user;
        $this->resetPasswordToken = $resetPasswordToken;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getResetPasswordToken(): ResetPasswordToken
    {
        return $this->resetPasswordToken;
    }
}
