<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"event:read"}},
 *     "denormalization_context"={"groups"={"event:write"}}
 * })
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"event:read","project:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"event:read","project:read"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"event:read","project:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"event:read","project:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"event:read","project:read"})
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"event:read","project:read"})
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="events")
     * @Groups({"event:read"})
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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
