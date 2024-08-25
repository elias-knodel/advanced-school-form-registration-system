<?php

namespace App\Core\Doctrine\Lifecycle;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait that adds a "created_at" and "updated_at" field.
 *
 * This trait provides functionality to automatically manage the timestamps
 * for when an entity is created and updated.
 *
 * This is realized using Doctrine lifecycle callbacks. To use this trait you must:
 *  - include it in your entity class as normal.
 *  - Add the {@see ORM\HasLifecycleCallbacks} annotation to your entity.
 */
trait TimestampableTrait
{
    /**
     * @var \DateTimeImmutable|null immutable date that specifies when the current entity has been persisted
     */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var \DateTimeImmutable|null date that specifies when the current entity was last updated
     */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @throws TimestampableLifecycleException
     *
     * @internal this function is a Doctrine lifecycle callback and should not be called manually
     */
    #[ORM\PrePersist]
    public function initializeTrackingTimestamps(): void
    {
        if (null !== $this->createdAt) {
            throw new TimestampableLifecycleException('Cannot initialize already initialized timestamps!');
        }

        $this->createdAt = $this->createCreatedAtDate();
    }

    /**
     * @throws TimestampableLifecycleException
     *
     * @internal this function is a Doctrine lifecycle callback and should not be called manually
     */
    #[ORM\PreUpdate]
    public function updateTrackingTimestamps(): void
    {
        if (null === $this->createdAt) {
            throw new TimestampableLifecycleException('Cannot update not initialized timestampable');
        }

        $this->updatedAt = $this->createUpdatedAtDate();
    }

    protected function createCreatedAtDate(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }

    protected function createUpdatedAtDate(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
