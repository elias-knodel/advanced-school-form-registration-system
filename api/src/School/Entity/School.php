<?php

namespace App\School\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Core\Doctrine\Lifecycle\TimestampableTrait;
use App\School\Repository\SchoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ApiResource]
#[ORM\Entity(repositoryClass: SchoolRepository::class)]
#[ORM\HasLifecycleCallbacks]
class School
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    /**
     * @var Collection<int, CustomForm>
     */
    #[ORM\OneToMany(mappedBy: 'school', targetEntity: CustomForm::class, orphanRemoval: true)]
    private Collection $customForms;

    /**
     * @var Collection<int, CustomField>
     */
    #[ORM\OneToMany(mappedBy: 'school', targetEntity: CustomField::class, orphanRemoval: true)]
    private Collection $customFields;

    /**
     * @var Collection<int, SchoolStaff>
     */
    #[ORM\OneToMany(mappedBy: 'school', targetEntity: SchoolStaff::class, orphanRemoval: true)]
    private Collection $schoolStaff;

    public function __construct()
    {
        $this->customForms = new ArrayCollection();
        $this->customFields = new ArrayCollection();
        $this->schoolStaff = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return Collection<int, CustomForm>
     */
    public function getCustomForms(): Collection
    {
        return $this->customForms;
    }

    public function addCustomForm(CustomForm $customForm): static
    {
        if (!$this->customForms->contains($customForm)) {
            $this->customForms->add($customForm);
            $customForm->setSchool($this);
        }

        return $this;
    }

    public function removeCustomForm(CustomForm $customForm): static
    {
        if ($this->customForms->removeElement($customForm)) {
            // set the owning side to null (unless already changed)
            if ($customForm->getSchool() === $this) {
                $customForm->setSchool(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CustomField>
     */
    public function getCustomFields(): Collection
    {
        return $this->customFields;
    }

    public function addCustomField(CustomField $customField): static
    {
        if (!$this->customFields->contains($customField)) {
            $this->customFields->add($customField);
            $customField->setSchool($this);
        }

        return $this;
    }

    public function removeCustomField(CustomField $customField): static
    {
        if ($this->customFields->removeElement($customField)) {
            // set the owning side to null (unless already changed)
            if ($customField->getSchool() === $this) {
                $customField->setSchool(null);
            }
        }

        return $this;
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
            $schoolStaff->setSchool($this);
        }

        return $this;
    }

    public function removeSchoolStaff(SchoolStaff $schoolStaff): static
    {
        if ($this->schoolStaff->removeElement($schoolStaff)) {
            // set the owning side to null (unless already changed)
            if ($schoolStaff->getSchool() === $this) {
                $schoolStaff->setSchool(null);
            }
        }

        return $this;
    }
}
