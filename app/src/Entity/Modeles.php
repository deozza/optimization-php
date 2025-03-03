<?php

namespace App\Entity;

use App\Repository\ModelesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ModelesRepository::class)]
#[ORM\Table(name: "modeles")]
#[ORM\Index(columns: ["status"], name: "status_idx")]
#[ORM\Index(columns: ["availability"], name: "availability_idx")]
class Modeles
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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: "text")]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $variation = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $availability = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $banner = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $page = null;

    #[ORM\OneToMany(mappedBy: "modele_entity", targetEntity: Galaxy::class)]
    private Collection $galaxies;

    #[ORM\OneToMany(mappedBy: "modele", targetEntity: ModelesFiles::class, fetch: "EXTRA_LAZY")]
    private Collection $files;

    public function __construct()
    {
        $this->galaxies = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
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

    public function getVariation(): ?string
    {
        return $this->variation;
    }

    public function setVariation(string $variation): static
    {
        $this->variation = $variation;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getAvailability(): ?string
    {
        return $this->availability;
    }

    public function setAvailability(?string $availability): static
    {
        $this->availability = $availability;

        return $this;
    }

    public function getBanner(): ?string
    {
        return $this->banner;
    }

    public function setBanner(?string $banner): static
    {
        $this->banner = $banner;

        return $this;
    }

    public function getPage(): ?string
    {
        return $this->page;
    }

    public function setPage(?string $page): static
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return Collection<int, Galaxy>
     */
    public function getGalaxies(): Collection
    {
        return $this->galaxies;
    }

    /**
     * @return Collection<int, ModelesFiles>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }
}