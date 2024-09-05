<?php

namespace App\User\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Core\Doctrine\Lifecycle\TimestampableTrait;
use App\Repository\User\Entity\EmailVerificationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;

#[ApiResource()]
#[UniqueEntity(['verificationKey'], message: 'Something unexpected happened please try again later')]
#[ORM\Entity(repositoryClass: EmailVerificationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class EmailVerification
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\Column(length: 64, unique: true)]
    private ?string $verificationKey = null;

    public function getId(): ?Uuid
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
