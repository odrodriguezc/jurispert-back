<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;
use Doctrine\ORM\Mapping\PrePersist;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 * @HasLifecycleCallbacks
 * @ApiResource(
 *      collectionOperations={
 *         "get",
 *          "post",
 *      },
 *     itemOperations={
 *         "get",
 *          "put",
 *          "delete"={"security": "is_granted('ROLE_ADMIN')"},
 *          "patch"
 *     },
 *      attributes={
 *          "normalization_context"={"groups"={"event:read"}},
 *          "denormalization_context"={"groups"={"event:write"}}
 *      }
 * )
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
     * @Groups({"event:read", "event:write","project:read"})
     *  @Assert\NotNull(message="Le title ne doit pas être null")
     * @Assert\Length(min=2, minMessage="Le title doit contenir au moins 2 caractère")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"event:read", "event:write","project:read"})
     * @Assert\NotNull(message="La description ne doit pas être null")
     * @Assert\Length(min=10, minMessage="La desription doit contenir au moins 10 caractère")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"event:read","project:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"event:read", "event:write", "project:read"})
     * @Assert\NotNull(message="La date limite ne doit pas être null")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"event:read", "event:write", "project:read"})
     * 
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="events")
     * @Groups({"event:read", "event:write"})
     * @Assert\NotNull(message="Il faut inscrire l'evennement dans un project")
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

    /** @PrePersist */
    public function createDate()
    {
        $date = new \DateTime();
        if (!$this->getCreatedAt()) {
            $this->createdAt = $date;
        }
    }
}
