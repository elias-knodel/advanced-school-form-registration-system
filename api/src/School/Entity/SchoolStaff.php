<?php

namespace App\School\Entity;

use App\Core\Doctrine\Lifecycle\TimestampableTrait;
use App\School\Enum\SchoolStaffRole;
use App\School\Repository\SchoolStaffRepository;
use App\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SchoolStaffRepository::class)]
#[ORM\HasLifecycleCallbacks]
class SchoolStaff
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'schoolStaff')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $employee = null;

    #[ORM\ManyToOne(inversedBy: 'schoolStaff')]
    #[ORM\JoinColumn(nullable: false)]
    private ?School $school = null;

    #[ORM\Column(type: 'string', enumType: SchoolStaffRole::class)]
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
