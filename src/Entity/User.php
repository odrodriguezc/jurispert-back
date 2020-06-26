<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @HasLifecycleCallbacks
 * @UniqueEntity("email")
 * @ApiResource(
 * collectionOperations={
 *         "get"
 *      },
 *     itemOperations={
 *         "get",
 *          "put"={"security":"is_granted('ROLE_USER') and user == object "}
 *     },
 *  attributes={
 *     "normalization_context"={"groups"={"user:read"}},
 *     "denormalization_context"={"groups"={"user:write"}}
 *  }
 * )
 * 
 */
class User implements UserInterface
{

    const ROLES = [
        'ROLE_ADMIN',
        'ROLE_MANAGER',
        'ROLE_USER'
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user:read", "project:read", "participation:read"})
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user:read", "project:read", "participation:read", "user:write"})
     * @Assert\NotBlank(message="L'adresse email est obligatoire")
     * @Assert\NotNull(message="L'adresse email est obligatoire")
     * @Assert\Email(message="L'adresse doit respecter le format: 'example@mail.xx'")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user:read", "project:read", "user:write"})
     * @Assert\NotBlank(message="Un role doit être sumministé à l'utilisateur. Ex. Administrateur", groups="admin")
     * @Assert\NotNull(message="Un role doit être sumministé à l'utilisateur. Ex. Administrateur", groups="admin")
     * 
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"user:read"})
     * @Assert\NotBlank(message="Le mot de passe est obligatoire", groups="admin")
     * @Assert\NotNull(message="Le mot de passe ne doit pas être null", groups="admin")
     * @Assert\Length(min=5, minMessage="le mot de passe doit contenir au moins 5 caracteres", groups="admin")
     */
    private $password;

    /**
     * 
     * @Assert\EqualTo(propertyPath="password", message="Le mot de passe doit etre identique", groups="admin")
     */
    public $confirmation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write"})
     * @Assert\NotBlank(message="Un prenon doit etre suministré")
     * @Assert\NotNull(message="Le prenom ne doit pas être null")
     * @Assert\Length(min=2, minMessage="Le prenom doit contenir au moins 2 caractère")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write"})
     * @Assert\NotBlank(message="Un non doit etre suministré")
     * @Assert\NotNull(message="Le nom ne doit pas être null")
     * @Assert\Length(min=2, minMessage="Le nom doit contenir au moins 2 caractère")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write"})
     * @Assert\NotBlank(message="Le poste de l'utilisateur doit être suministré", groups="admin")
     * @Assert\NotNull(message="Le poste ne doit pas être null", groups="admin")
     * @Assert\Length(min=2, minMessage="Le poste doit contenir au moins 2 caractère", groups="admin")
     */
    private $post;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("user:read")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("user:read")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Project::class, mappedBy="owner")
     * @Groups("user:read")
     */
    private $projects;

    /**
     * @ORM\OneToMany(targetEntity=Participation::class, mappedBy="user")
     * @Groups("user:read")
     */
    private $participations;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->participations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @Groups({"project:read", "participation:read"})
     */
    public function getFullName(): ?string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPost(): ?string
    {
        return $this->post;
    }

    public function setPost(string $post): self
    {
        $this->post = $post;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setOwner($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->contains($project)) {
            $this->projects->removeElement($project);
            // set the owning side to null (unless already changed)
            if ($project->getOwner() === $this) {
                $project->setOwner(null);
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
            $participation->setUser($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): self
    {
        if ($this->participations->contains($participation)) {
            $this->participations->removeElement($participation);
            // set the owning side to null (unless already changed)
            if ($participation->getUser() === $this) {
                $participation->setUser(null);
            }
        }

        return $this;
    }

    /** @PrePersist */
    public function createDate()
    {
        $date = new DateTime();
        if (!$this->getCreatedAt()) {
            $this->createdAt = $date;
        }
        $this->updatedAt = $date;
    }
}
