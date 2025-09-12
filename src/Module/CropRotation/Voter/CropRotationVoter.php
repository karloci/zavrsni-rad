<?php

namespace App\Module\CropRotation\Voter;

use App\Entity\CropRotation;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class CropRotationVoter extends Voter
{
    private AccessDecisionManagerInterface $accessDecisionManager;

    public function __construct(AccessDecisionManagerInterface $accessDecisionManager)
    {
        $this->accessDecisionManager = $accessDecisionManager;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, ["READ", "UPDATE", "DELETE"]) && $subject instanceof CropRotation;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var CropRotation $cropRotation */
        $cropRotation = $subject;

        $isSameFarm = $user->getFarm()?->getId()->equals($cropRotation->getField()->getFarm()?->getId());
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
