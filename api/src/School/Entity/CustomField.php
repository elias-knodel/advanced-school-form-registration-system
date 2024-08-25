<?php

namespace App\School\Entity;

use App\Core\Doctrine\Lifecycle\TimestampableTrait;
use App\School\Repository\CustomFieldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private ?School $School = null;

    /**
     * @var Collection<int, CustomFormField>
     */
    #[ORM\OneToMany(mappedBy: 'Field', targetEntity: CustomFormField::class, orphanRemoval: true)]
    private Collection $customFormFields;

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
        return $this->School;
    }

    public function setSchool(?School $School): static
    {
        $this->School = $School;

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
}
