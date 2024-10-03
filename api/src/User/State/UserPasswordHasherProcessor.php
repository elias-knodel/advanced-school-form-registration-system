<?php

namespace App\User\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\User\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsDecorator('api_platform.doctrine.orm.state.persist_processor')]
final readonly class UserPasswordHasherProcessor implements ProcessorInterface
{
    /**
     * @param ProcessorInterface<User, User> $innerProcessor
     */
    public function __construct(
        private ProcessorInterface          $innerProcessor,
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!$data instanceof User || !$data->getPlainPassword()) {
            return $this->innerProcessor->process($data, $operation, $uriVariables, $context);
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $data,
            $data->getPlainPassword()
        );
        $data->setPassword($hashedPassword);
        $data->eraseCredentials();

        return $this->innerProcessor->process($data, $operation, $uriVariables, $context);
    }
}
