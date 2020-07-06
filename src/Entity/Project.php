<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PrePersist;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 * 
 * @HasLifecycleCallbacks
 * @ApiResource(
 *      collectionOperations={
 *         "get",
 *          "post"={"security": "is_granted('ROLE_ADMIN')"}
 *      },
 *     itemOperations={
 *         "get",
 *          "put",
 *          "delete"={"security": "is_granted('ROLE_ADMIN')"},
 *          "patch"
 *     },
 *      attributes={
 *          "normalization_context"={"groups"={"project:read"}},
 *          "denormalization_context"={"groups"={"project:write"}}
 * })
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"project:read","user:read", "task:read", "event:read", "participation:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"project:read", "project:write","user:read", "task:read", "event:read", "participation:read"})
     * @Assert\NotNull(message="Le title ne doit pas être null")
     * @Assert\Length(min=2, minMessage="Le title doit contenir au moins 2 caractère")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"project:read", "project:write","user:read", "task:read", "event:read", "participation:read"})
     * 
     */
    private $shortDescription;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"project:read", "project:write", "user:read", "task:read", "event:read", "participation:read"})
     * @Assert\NotNull(message="La description ne doit pas être null")
     * @Assert\Length(min=10, minMessage="La description doit contenir au moins 10 caractère")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"project:read","user:read", "task:read", "event:read", "participation:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"project:read", "project:write", "user:read", "task:read", "event:read", "participation:read"})
     */
    private $deadline;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"project:read", "project:write", "user:read","task:read", "event:read", "participation:read"})
     */
    private $adversary;

    /**
     * @ORM\ManyToMany(targetEntity=Customer::class, inversedBy="projects")
     * @Groups({"project:read", "project:write"})
     * @Assert\NotNull(message="Le client ne doit pas être null")
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="projects")
     * @Groups({"project:read", "task:read", "event:read", "participation:read"})
     */
    private $owner;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Groups({"project:read","user:read","task:read", "event:read", "participation:read"})
     */
    private $stages = [];

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"project:read", "project:write", "user:read", "task:read", "event:read", "participation:read"})
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="project")
     * @Groups({"project:read", "project:write"})
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity=Participation::class, mappedBy="project", cascade="persist")
     * @Groups({"project:read"})
     * 
     */
    private $participations;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"project:read", "project:write", "user:read", "task:read", "event:read", "participation:read"})
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="project")
     * @Groups({"project:read"})
     */
    private $events;

    public function __construct()
    {
        $this->customer = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->participations = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

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

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
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

    public function setDeadline(?\DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getAdversary(): ?string
    {
        return $this->adversary;
    }

    public function setAdversary(string $adversary): self
    {
        $this->adversary = $adversary;

        return $this;
    }

    /**
     * @return Collection|Customer[]
     */
    public function getCustomer(): Collection
    {
        return $this->customer;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customer->contains($customer)) {
            $this->customer[] = $customer;
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customer->contains($customer)) {
            $this->customer->removeElement($customer);
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getStages(): ?array
    {
        return $this->stages;
    }

    public function setStages(?array $stages): self
    {
        $this->stages = $stages;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setProject($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Participation[]
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): self
    {
        if (!$this->participations->contains($participation)) {
            $this->participations[] = $participation;
            $participation->setProject($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): self
    {
        if ($this->participations->contains($participation)) {
            $this->participations->removeElement($participation);
            // set the owning side to null (unless already changed)
            if ($participation->getProject() === $this) {
                $participation->setProject(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setProject($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getProject() === $this) {
                $event->setProject(null);
            }
        }

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

    /** @PrePersist */
    public function createStages()
    {
        if ($this->stages) {
            return;
        }
        if ($this->category === 'PROTOTYPE') {
            $this->setStages(['PRE-NEGOTIATION', 'COUR-NEGOTIATIONS', 'TRIAL-INSTRUCTIONS', 'TRIAL-ALEGATIONS', 'TRIAL-JUDGMENT']);
        }
    }

    /** @PrePersist */
    public function createStatus()
    {
        if ($this->getStatus()) {
            return;
        }
        if ($this->category === 'PROTOTYPE') {
            $this->setStatus('PRE-NEGOTIATION');
        }
    }

    public function getUsers(): array
    {
        /** @var Participation[] */
        $participations = $this->getParticipations();

        /** @var User[] */
        $users = [];

        $users[] = $this->getOwner();

        foreach ($participations as $participation) {
            $users[] = $participation->getUser();
        }

        return $users;
    }

    /**
     * Undocumented function
     *
     * @return array<User> 
     */
    public function getManagers(): array
    {
        $users = [];

        foreach ($this->participations as $participation) {
            if ($participation->getRole() === 'MANAGER') {
                $users[] = $participation->getUser();
            }
        }

        return $users;
    }
}
