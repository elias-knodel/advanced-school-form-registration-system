<?php

namespace App\User\DataPersister;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\User\Dto\UserRegistrationInput;
use App\User\Entity\EmailVerification;
use App\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegistrationPersister implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: PersistProcessor::class)]
        private ProcessorInterface          $innerProcessor,
        private EntityManagerInterface      $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws RandomException
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (!$data instanceof UserRegistrationInput) {
            return;
        }

        // Create User entity
        $user = new User();
        $user->setEmail($data->email);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $data->password);
        $user->setPlainPassword($data->password);

        // Call the doctrine persist processor
        $this->innerProcessor->process($user, $operation, $uriVariables, $context);

        // AAAAAAAAAA

        $this->entityManager->persist($user);

        // Create EmailVerification entity
        $emailVerification = new EmailVerification();
        $emailVerification->setOwner($user);
        $emailVerification->setVerificationKey(bin2hex(random_bytes(32)));

        $this->entityManager->persist($emailVerification);

        // Send verification email
        $email = (new Email())
            ->from('no-reply@example.com')
            ->to($user->getEmail())
            ->subject('Please verify your email')
            ->text('Please use the following token to verify your email: ' . $emailVerification->getVerificationKey());

//        $this->mailer->send($email);

        // Flush all changes
        $this->entityManager->flush();
    }
}
