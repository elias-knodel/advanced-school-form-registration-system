<?php

namespace App\User\Security\Voter;

use App\User\Entity\User;
use LogicException;
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
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        /** @var User $userSubject */
        $userSubject = $subject;

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return match ($attribute) {
            self::VIEW, self::EDIT => $this->canEdit($userSubject, $user),
            default => throw new LogicException('This code should not be reached!')
        };
    }

    private function canEdit(User $userSubject, User $user): bool
    {
        if ($userSubject->getId()->equals($user->getId())) {
            return true;
        }

        return false;
    }
}
