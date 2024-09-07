<?php

namespace App\School\Entity;

use App\Core\Doctrine\Lifecycle\TimestampableTrait;
use App\School\Enum\CustomFieldType;
use App\School\Repository\CustomFieldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CustomFieldRepository::class)]
#[ORM\HasLifecycleCallbacks]
class CustomField
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'customFields')]
    #[ORM\JoinColumn(nullable: false)]
    private ?School $school = null;

    /**
     * @var Collection<int, CustomFormField>
     */
    #[ORM\OneToMany(mappedBy: 'Field', targetEntity: CustomFormField::class, orphanRemoval: true)]
    private Collection $customFormFields;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $tooltip = null;

    #[ORM\Column(length: 255)]
    private ?CustomFieldType $type = null;

    public function __construct()
    {
        $this->customFormFields = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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

    /**
     * @return Collection<int, CustomFormField>
     */
    public function getCustomFormFields(): Collection
    {
        return $this->customFormFields;
    }

    public function addCustomFormField(CustomFormField $customFormField): static
    {
        if (!$this->customFormFields->contains($customFormField)) {
            $this->customFormFields->add($customFormField);
            $customFormField->setField($this);
        }

        return $this;
    }

    public function removeCustomFormField(CustomFormField $customFormField): static
    {
        if ($this->customFormFields->removeElement($customFormField)) {
            // set the owning side to null (unless already changed)
            if ($customFormField->getField() === $this) {
                $customFormField->setField(null);
            }
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTooltip(): ?string
    {
        return $this->tooltip;
    }

    public function setTooltip(string $tooltip): static
    {
        $this->tooltip = $tooltip;

        return $this;
    }

    public function getType(): ?CustomFieldType
    {
        return $this->type;
    }

    public function setType(CustomFieldType $type): static
    {
        $this->type = $type;

        return $this;
    }
}
