<?php

namespace App\Module\City\Voter;

use App\Entity\City;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class CityVoter extends Voter
{
    private AccessDecisionManagerInterface $accessDecisionManager;

    public function __construct(AccessDecisionManagerInterface $accessDecisionManager)
    {
        $this->accessDecisionManager = $accessDecisionManager;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, ["READ", "UPDATE", "DELETE"]) && $subject instanceof City;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            "READ"             => true,
            "UPDATE", "DELETE" => $this->accessDecisionManager->decide($token, ["ROLE_ADMIN"]),
            default            => false
        };
    }
}
