<?php

namespace App\User\Entity;

use App\Repository\User\Entity\EmailVerificationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: EmailVerificationRepository::class)]
class EmailVerification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\Column(length: 64)]
    private ?string $verificationKey = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getVerificationKey(): ?string
    {
        return $this->verificationKey;
    }

    public function setVerificationKey(string $verificationKey): static
    {
        $this->verificationKey = $verificationKey;

        return $this;
    }
}
