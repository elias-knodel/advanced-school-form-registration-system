<?php

namespace App\School\Entity;

use App\Core\Doctrine\Lifecycle\TimestampableTrait;
use App\School\Repository\CustomFormFieldRepository;
use Doctrine\DBAL\Types\Types;
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
    private ?CustomForm $form = null;

    #[ORM\ManyToOne(inversedBy: 'customFormFields')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CustomField $field = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $position = null;

    #[ORM\Column(nullable: true)]
    private ?bool $half = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getForm(): ?CustomForm
    {
        return $this->form;
    }

    public function setForm(?CustomForm $form): static
    {
        $this->form = $form;

        return $this;
    }

    public function getField(): ?CustomField
    {
        return $this->field;
    }

    public function setField(?CustomField $field): static
    {
        $this->field = $field;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function isHalf(): ?bool
    {
        return $this->half;
    }

    public function setHalf(?bool $half): static
    {
        $this->half = $half;

        return $this;
    }
}
