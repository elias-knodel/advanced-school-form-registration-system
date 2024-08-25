<?php

namespace App\User\Dto;

use Symfony\Component\Validator\Constraints as Assert;
class UserRegistrationInput
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    public string $password;

    #[Assert\NotBlank]
    public string $username; // For PublicProfile

    // Additional fields for customer secure information, e.g., address, payment method
    public ?string $address = null;
    public ?string $paymentMethod = null;
}
