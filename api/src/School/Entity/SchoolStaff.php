<?php

namespace App\School\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Core\Doctrine\Lifecycle\TimestampableTrait;
use App\School\Enum\SchoolStaffRole;
use App\School\Repository\SchoolStaffRepository;
use App\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    uriTemplate: '/schools/{school}/staff{._format}',
    operations: [
        new GetCollection(),
        new Get(
            validationContext: ['groups' => ['school_staff:read']],
        ),
        new Patch(
            validationContext: ['groups' => ['school_staff:update']],
        ),
        new Delete(),
        new Post(
            validationContext: ['groups' => ['school_staff:create']],
        ),
    ],
    normalizationContext: ['groups' => ['school_staff:read']],
    denormalizationContext: ['groups' => ['school_staff:create', 'school_staff:update']],
)]
#[ApiResource(
    uriTemplate: '/users/{employee}/school{._format}',
    operations: [
        new GetCollection(),
        new Get(
            validationContext: ['groups' => ['school_staff:read']],
        ),
        new Patch(
            validationContext: ['groups' => ['school_staff:update']],
        ),
        new Delete(),
        new Post(
            validationContext: ['groups' => ['school_staff:create']],
        ),
    ],
    normalizationContext: ['groups' => ['school_staff:read']],
    denormalizationContext: ['groups' => ['school_staff:create', 'school_staff:update']],
)]
#[ORM\Entity(repositoryClass: SchoolStaffRepository::class)]
#[ORM\HasLifecycleCallbacks]
class SchoolStaff
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['school_staff:read'])]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'schoolStaff')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['school_staff:read', 'school_staff:create'])]
    private ?User $employee = null;

    #[ORM\ManyToOne(inversedBy: 'schoolStaff')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['school_staff:read', 'school_staff:create'])]
    private ?School $school = null;

    #[ORM\Column(enumType: SchoolStaffRole::class)]
    #[Groups(['school_staff:read', 'school_staff:create', 'school_staff:update'])]
    private ?SchoolStaffRole $role = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEmployee(): ?User
    {
        return $this->employee;
    }

    public function setEmployee(?User $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function setSchool(?School $school): static
    {
        $this->school = $school;

        return $this;
    }

    public function getRole(): ?SchoolStaffRole
    {
        return $this->role;
    }

    public function setRole(?SchoolStaffRole $role): void
    {
        $this->role = $role;
    }
}
