<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TaskRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"task:read"}},
 *     "denormalization_context"={"groups"={"task:write"}}
 * })
 */
class Task
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"task:read","project:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"task:read","project:read"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"task:read","project:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"task:read","project:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"task:read","project:read"})
     */
    private $deadline;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"task:read","project:read"})
     */
    private $completed;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="tasks")
     * @Groups({"task:read"})
     */
    private $project;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(\DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getCompleted(): ?bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): self
    {
        $this->completed = $completed;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }
}
