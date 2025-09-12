<?php

namespace App\Module\Season\Voter;

use App\Entity\Season;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class SeasonVoter extends Voter
{
    private AccessDecisionManagerInterface $accessDecisionManager;

    public function __construct(AccessDecisionManagerInterface $accessDecisionManager)
    {
        $this->accessDecisionManager = $accessDecisionManager;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, ["READ", "UPDATE", "DELETE"]) && $subject instanceof Season;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Season $season */
        $season = $subject;

        $isSameFarm = $user->getFarm()?->getId()->equals($season->getFarm()?->getId());
        $isAdmin = $this->accessDecisionManager->decide($token, ["ROLE_ADMIN"]);
        $isOwner = $this->accessDecisionManager->decide($token, ["ROLE_OWNER"]);

        return match ($attribute) {
            "READ"   => $isSameFarm || $isAdmin,
            "UPDATE" => ($isSameFarm && $isOwner) || $isAdmin,
            "DELETE" => $isOwner,
            default  => false,
        };
    }
}
