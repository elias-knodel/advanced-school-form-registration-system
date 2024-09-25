<?php

namespace App\User\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Core\Doctrine\Lifecycle\TimestampableTrait;
use App\School\Entity\SchoolStaff;
use App\User\Dto\UserRegistrationInput;
use App\User\Repository\UserRepository;
use App\User\State\UserRegistrationProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(
            security: "is_granted('USER_VIEW', object)",
        ),
        new Patch(
            security: "is_granted('USER_EDIT', object)",
            validationContext: ['groups' => ['Default', 'user:update']],
        ),
        new Delete(
            security: "is_granted('USER_EDIT', object)",
        ),
        new Post(
            input: UserRegistrationInput::class,
            processor: UserRegistrationProcessor::class
        ),
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:create', 'user:update']],
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[Assert\Email(
        mode: Assert\Email::VALIDATION_MODE_STRICT
    )]
    #[Groups(['user:read', 'user:update'])]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Assert\PasswordStrength]
    #[Groups(['user:update'])]
    #[SerializedName('password')]
    private ?string $plainPassword = null;

    #[ORM\OneToOne(mappedBy: 'owner', targetEntity: EmailVerification::class, cascade: ['remove'])]
    private ?EmailVerification $emailVerification = null;

    /**
     * @var Collection<int, SchoolStaff>
     */
    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: SchoolStaff::class, orphanRemoval: true)]
    private Collection $schoolStaff;

    #[ORM\Column(nullable: true)]
    private ?bool $verified = null;

    public function __construct()
    {
        $this->schoolStaff = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, SchoolStaff>
     */
    public function getSchoolStaff(): Collection
    {
        return $this->schoolStaff;
    }

    public function addSchoolStaff(SchoolStaff $schoolStaff): static
    {
        if (!$this->schoolStaff->contains($schoolStaff)) {
            $this->schoolStaff->add($schoolStaff);
            $schoolStaff->setEmployee($this);
        }

        return $this;
    }

    public function removeSchoolStaff(SchoolStaff $schoolStaff): static
    {
        if ($this->schoolStaff->removeElement($schoolStaff)) {
            // set the owning side to null (unless already changed)
            if ($schoolStaff->getEmployee() === $this) {
                $schoolStaff->setEmployee(null);
            }
        }

        return $this;
    }

    public function isVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(?bool $verified): static
    {
        $this->verified = $verified;

        return $this;
    }
}
