<?php

namespace App\School\Entity;

use App\Core\Doctrine\Lifecycle\TimestampableTrait;
use App\School\Repository\CustomFormRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CustomFormRepository::class)]
#[ORM\HasLifecycleCallbacks]
class CustomForm
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'customForms')]
    #[ORM\JoinColumn(nullable: false)]
    private ?School $School = null;

    /**
     * @var Collection<int, CustomFormField>
     */
    #[ORM\OneToMany(mappedBy: 'Form', targetEntity: CustomFormField::class, orphanRemoval: true)]
    private Collection $customFormFields;

    /**
     * @var Collection<int, SchoolRegisterRequest>
     */
    #[ORM\OneToMany(mappedBy: 'CustomForm', targetEntity: SchoolRegisterRequest::class, orphanRemoval: true)]
    private Collection $schoolRegisterRequests;

    public function __construct()
    {
        $this->customFormFields = new ArrayCollection();
        $this->schoolRegisterRequests = new ArrayCollection();
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
            $customFormField->setForm($this);
        }

        return $this;
    }

    public function removeCustomFormField(CustomFormField $customFormField): static
    {
        if ($this->customFormFields->removeElement($customFormField)) {
            // set the owning side to null (unless already changed)
            if ($customFormField->getForm() === $this) {
                $customFormField->setForm(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SchoolRegisterRequest>
     */
    public function getSchoolRegisterRequests(): Collection
    {
        return $this->schoolRegisterRequests;
    }

    public function addSchoolRegisterRequest(SchoolRegisterRequest $schoolRegisterRequest): static
    {
        if (!$this->schoolRegisterRequests->contains($schoolRegisterRequest)) {
            $this->schoolRegisterRequests->add($schoolRegisterRequest);
            $schoolRegisterRequest->setCustomForm($this);
        }

        return $this;
    }

    public function removeSchoolRegisterRequest(SchoolRegisterRequest $schoolRegisterRequest): static
    {
        if ($this->schoolRegisterRequests->removeElement($schoolRegisterRequest)) {
            // set the owning side to null (unless already changed)
            if ($schoolRegisterRequest->getCustomForm() === $this) {
                $schoolRegisterRequest->setCustomForm(null);
            }
        }

        return $this;
    }
}
