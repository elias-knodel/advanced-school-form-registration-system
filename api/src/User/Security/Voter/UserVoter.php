<?php

namespace App\User\Security\Voter;

use App\User\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    public const VIEW = 'USER_VIEW';
    public const EDIT = 'USER_EDIT';

    public function __construct(
        private readonly Security $security
    )
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT])
            && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        assert($subject instanceof User);

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::VIEW:
            case self::EDIT:
                if ($subject->getId() === $user->getId()) {
                    return true;
                }
                break;
        }

        return false;
    }
}
