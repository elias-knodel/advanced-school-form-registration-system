<?php

namespace App\User\Dto;

use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
class UserRegistrationInput
{
    #[ApiProperty(
        example: "user@example.com"
    )]
    #[Assert\NotBlank]
    #[Assert\Email(
        mode: Assert\Email::VALIDATION_MODE_STRICT
    )]
    #[Groups(['user:create'])]
    public string $email;

    #[ApiProperty(
        example: "Str0ngP@ssw0rd!"
    )]
    #[Assert\NotBlank]
    #[Assert\PasswordStrength]
    #[Groups(['user:create'])]
    public string $password;

    #[ApiProperty(
        example: "username123"
    )]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max: 32)]
    #[Groups(['user:create'])]
    public string $username;
}
