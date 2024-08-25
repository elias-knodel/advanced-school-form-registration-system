<?php

namespace App\School\Entity;

use App\Core\Doctrine\Lifecycle\TimestampableTrait;
use App\School\Repository\CustomFormFieldRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CustomFormFieldRepository::class)]
#[ORM\HasLifecycleCallbacks]
class CustomFormField
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'customFormFields')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CustomForm $Form = null;

    #[ORM\ManyToOne(inversedBy: 'customFormFields')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CustomField $Field = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getForm(): ?CustomForm
    {
        return $this->Form;
    }

    public function setForm(?CustomForm $Form): static
    {
        $this->Form = $Form;

        return $this;
    }

    public function getField(): ?CustomField
    {
        return $this->Field;
    }

    public function setField(?CustomField $Field): static
    {
        $this->Field = $Field;

        return $this;
    }
}
