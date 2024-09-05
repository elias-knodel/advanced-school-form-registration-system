<?php

namespace App\User\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Validator\ValidatorInterface;
use App\User\Dto\UserRegistrationInput;
use App\User\Entity\EmailVerification;
use App\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class UserRegistrationProcessor implements ProcessorInterface
{
    public function __construct(
        private ValidatorInterface          $validator,
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface      $manager
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): ?User
    {
        if (!$data instanceof UserRegistrationInput) {
            return null;
        }

        // Create User entity
        $user = new User();
        $user->setEmail($data->email);

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $data->password
        );
        $user->setPassword($hashedPassword);

        $this->validator->validate($user);

        // Create EmailVerification entity
        $emailVerification = new EmailVerification();
        $emailVerification->setOwner($user);
        $emailVerification->setVerificationKey(bin2hex(random_bytes(32)));

        $this->validator->validate($emailVerification);

        $this->manager->persist($emailVerification);
        $this->manager->flush();

        return $user;
    }
}
