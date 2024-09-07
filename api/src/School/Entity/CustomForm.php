<?php

namespace App\School\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Core\Doctrine\Lifecycle\TimestampableTrait;
use App\School\Repository\CustomFormRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    uriTemplate: '/schools/{school}/custom_form.{_format}',
)]
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
    private ?School $school = null;

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

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $order = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function setOrder(int $order): static
    {
        $this->order = $order;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }
}
