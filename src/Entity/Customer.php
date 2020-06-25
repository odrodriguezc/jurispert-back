<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 *  @ApiResource(attributes={
 *     "normalization_context"={"groups"={"customer:read"}},
 *     "denormalization_context"={"groups"={"customer:write"}}
 * })
 */
class Customer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"customer:read","project:read", "user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"customer:read","project:read", "user:read"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"customer:read","project:read", "user:read"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer:read","project:read", "user:read"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer:read","project:read", "user:read"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer:read","project:read", "user:read"})
     */
    private $company;

    /**
     * @ORM\ManyToMany(targetEntity=Project::class, mappedBy="customer")
     */
    private $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

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
            $project->addCustomer($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->contains($project)) {
            $this->projects->removeElement($project);
            $project->removeCustomer($this);
        }

        return $this;
    }
}
