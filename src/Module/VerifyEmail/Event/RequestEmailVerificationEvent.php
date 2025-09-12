<?php

namespace App\Module\VerifyEmail\Event;

use App\Entity\User;
use App\Entity\VerifyEmailToken;
use Symfony\Contracts\EventDispatcher\Event;

class RequestEmailVerificationEvent extends Event
{
    public const string NAME = "user.request_email_verification";

    private User $user;

    private VerifyEmailToken $verifyEmailToken;

    public function __construct(User $user, VerifyEmailToken $verifyEmailToken)
    {
        $this->user = $user;
        $this->verifyEmailToken = $verifyEmailToken;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getVerifyEmailToken(): VerifyEmailToken
    {
        return $this->verifyEmailToken;
    }

    public function setVerifyEmailToken(VerifyEmailToken $verifyEmailToken): void
    {
        $this->verifyEmailToken = $verifyEmailToken;
    }
}
