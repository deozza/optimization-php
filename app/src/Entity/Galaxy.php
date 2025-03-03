<?php

namespace App\Entity;

use App\Repository\GalaxyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GalaxyRepository::class)]
#[ORM\Table(name: "galaxy")]
#[ORM\Index(columns: ["status"], name: "status_idx")]
#[ORM\Index(columns: ["modele"], name: "modele_idx")]
class Galaxy
{
    #[ORM\Id]
    #[ORM\Column(type: "string", length: 36)]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $status = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $sort = null;

    #[ORM\Column(length: 255)]
    private ?string $user_created = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $date_created = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $user_updated = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $date_updated = null;

    #[ORM\ManyToOne(targetEntity: Modeles::class)]
    #[ORM\JoinColumn(name: "modele", referencedColumnName: "id")]
    private ?Modeles $modele_entity = null;

    #[ORM\Column(length: 255)]
    private ?string $modele = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(type: "text")]
    private ?string $description = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;
        
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(?int $sort): static
    {
        $this->sort = $sort;

        return $this;
    }

    public function getUserCreated(): ?string
    {
        return $this->user_created;
    }

    public function setUserCreated(string $user_created): static
    {
        $this->user_created = $user_created;

        return $this;
    }

    public function getDateCreated(): ?string
    {
        return $this->date_created ? $this->date_created->format('Y-m-d H:i:s') : null;
    }

    public function getDateCreatedObject(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(string|\DateTimeInterface $date_created): static
    {
        if (is_string($date_created)) {
            $this->date_created = new \DateTime($date_created);
        } else {
            $this->date_created = $date_created;
        }

        return $this;
    }

    public function getUserUpdated(): ?string
    {
        return $this->user_updated;
    }

    public function setUserUpdated(?string $user_updated): static
    {
        $this->user_updated = $user_updated;

        return $this;
    }

    public function getDateUpdated(): ?string
    {
        return $this->date_updated ? $this->date_updated->format('Y-m-d H:i:s') : null;
    }

    public function getDateUpdatedObject(): ?\DateTimeInterface
    {
        return $this->date_updated;
    }

    public function setDateUpdated($date_updated): static
    {
        if ($date_updated === null) {
            $this->date_updated = null;
        } elseif (is_string($date_updated)) {
            $this->date_updated = new \DateTime($date_updated);
        } else {
            $this->date_updated = $date_updated;
        }
    
        return $this;
    }

    public function getModeleEntity(): ?Modeles
    {
        return $this->modele_entity;
    }

    public function setModeleEntity(?Modeles $modele): static
    {
        $this->modele_entity = $modele;
        $this->modele = $modele ? $modele->getId() : null;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): static
    {
        $this->modele = $modele;

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

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}