<?php

namespace App\School\Security\Voter;

use App\School\Entity\School;
use App\User\Entity\User;
use LogicException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class SchoolStaffVoter extends Voter
{
    public const EDIT = 'SCHOOL_EDIT';
    public const VIEW = 'SCHOOL_VIEW';

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

        if (!$subject instanceof School) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        /** @var School $subject */
        assert ($subject instanceof School);

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return match ($attribute) {
            self::VIEW, self::EDIT => $this->canEdit($subject, $user),
            default => throw new LogicException('This code should not be reached!')
        };
    }

    private function canEdit(School $subject, User $user): bool
    {
        if ($subject->getSchoolStaff()->contains($user)) {
            return true;
        }

        return false;
    }
}
