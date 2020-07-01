<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ParticipationRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ParticipationRepository::class)
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
 *          "normalization_context"={"groups"={"participation:read"}},
 *          "denormalization_context"={"groups"={"participation:write"}}
 * })
 */
class Participation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"participation:read", "user:read", "project:read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="participations")
     * @Groups({"participation:read", "participation:write"})
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="participations")
     *  
     * @Groups({"participation:read", "project:read","participation:write"})
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"participation:read", "user:read", "project:read", "participation:write"})
     */
    private $role;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }
}
