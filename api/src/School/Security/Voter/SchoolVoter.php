<?php

namespace App\School\Security\Voter;

use App\School\Entity\School;
use App\User\Entity\User;
use LogicException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SchoolVoter extends Voter
{
    public const CREATE = 'SCHOOL_CREATE';
    public const EDIT = 'SCHOOL_EDIT';
    public const DELETE = 'SCHOOL_DELETE';

    public function __construct(
        private readonly Security $security
    )
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::CREATE, self::EDIT, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof School) {
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

        /** @var School $school */
        $school = $subject;

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return match ($attribute) {
            self::CREATE => $user->isVerified() ?? false,
            self::EDIT, => $this->canEdit($school, $user),
            self::DELETE => false,
            default => throw new LogicException('This code should not be reached!')
        };
    }

    private function canEdit(School $school, User $user): bool
    {
        if ($school->getSchoolStaff()->contains($user)) {
            return true;
        }

        return false;
    }
}
