<?php

namespace App\Entity;

use App\Repository\ModelesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModelesRepository::class)]
class Modeles
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(nullable: true)]
    private ?int $sort = null;

    #[ORM\Column(length: 255)]
    private ?string $user_created = null;

    #[ORM\Column(length: 255)]
    private ?string $date_created = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $user_updated = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $date_updated = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $variation = null;

    #[ORM\Column(nullable: true)]
    private ?int $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $availability = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $banner = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $page = null;

    /**
     * @var Collection<int, Galaxy>
     */
    #[ORM\OneToMany(targetEntity: Galaxy::class, mappedBy: 'modeleB')]
    private Collection $galaxies;

    /**
     * @var Collection<int, ModelesFiles>
     */
    #[ORM\OneToMany(targetEntity: ModelesFiles::class, mappedBy: 'modeles')]
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

    public function setSort(int $sort): static
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
        return $this->date_created;
    }

    public function setDateCreated(string $date_created): static
    {
        $this->date_created = $date_created;

        return $this;
    }

    public function getUserUpdated(): ?string
    {
        return $this->user_updated;
    }

    public function setUserUpdated(string $user_updated): static
    {
        $this->user_updated = $user_updated;

        return $this;
    }

    public function getDateUpdated(): ?string
    {
        return $this->date_updated;
    }

    public function setDateUpdated(string $date_updated): static
    {
        $this->date_updated = $date_updated;

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

    public function addGalaxy(Galaxy $galaxy): static
    {
        if (!$this->galaxies->contains($galaxy)) {
            $this->galaxies->add($galaxy);
            $galaxy->setModeleB($this);
        }

        return $this;
    }

    public function removeGalaxy(Galaxy $galaxy): static
    {
        if ($this->galaxies->removeElement($galaxy)) {
            // set the owning side to null (unless already changed)
            if ($galaxy->getModeleB() === $this) {
                $galaxy->setModeleB(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ModelesFiles>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(ModelesFiles $file): static
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
            $file->setModeles($this);
        }

        return $this;
    }

    public function removeFile(ModelesFiles $file): static
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getModeles() === $this) {
                $file->setModeles(null);
            }
        }

        return $this;
    }
}
