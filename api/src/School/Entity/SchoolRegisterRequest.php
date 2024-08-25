<?php

namespace App\School\Entity;

use App\Core\Doctrine\Lifecycle\TimestampableTrait;
use App\School\Repository\SchoolRegisterRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SchoolRegisterRequestRepository::class)]
#[ORM\HasLifecycleCallbacks]
class SchoolRegisterRequest
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'schoolRegisterRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CustomForm $CustomForm = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getCustomForm(): ?CustomForm
    {
        return $this->CustomForm;
    }

    public function setCustomForm(?CustomForm $CustomForm): static
    {
        $this->CustomForm = $CustomForm;

        return $this;
    }
}
