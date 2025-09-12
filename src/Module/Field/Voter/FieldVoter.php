<?php

namespace App\Module\Field\Voter;

use App\Entity\Field;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class FieldVoter extends Voter
{
    private AccessDecisionManagerInterface $accessDecisionManager;

    public function __construct(AccessDecisionManagerInterface $accessDecisionManager)
    {
        $this->accessDecisionManager = $accessDecisionManager;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, ["READ", "UPDATE", "DELETE"]) && $subject instanceof Field;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Field $field */
        $field = $subject;

        $isSameFarm = $user->getFarm()?->getId()->equals($field->getFarm()?->getId());
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
