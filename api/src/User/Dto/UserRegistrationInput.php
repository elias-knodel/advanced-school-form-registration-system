<?php

namespace App\User\Dto;

use Symfony\Component\Validator\Constraints as Assert;
class UserRegistrationInput
{
    #[Assert\NotBlank]
    #[Assert\Email(
        mode: Assert\Email::VALIDATION_MODE_STRICT
    )]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\PasswordStrength]
    public string $password;

    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max: 32)]
    public string $username; // For PublicProfile
}
