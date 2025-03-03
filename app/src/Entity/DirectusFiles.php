<?php

namespace App\Entity;

use App\Repository\DirectusFilesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DirectusFilesRepository::class)]
class DirectusFiles
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $storage = null;

    #[ORM\Column(length: 255, nullable: true)]
    public ?string $filename_disk= null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $filename_download = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $folder = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $uploaded_by = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $created_on = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $modified_by = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $modified_on = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $charset = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $filesize = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $width = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $height = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $duration = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $embed = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tags = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $metadata= null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $focal_point_x = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $focal_point_y = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tus_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tus_data = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $uploaded_on = null;

    /**
     * @var Collection<int, ModelesFiles>
     */
    #[ORM\OneToMany(targetEntity: ModelesFiles::class, mappedBy: 'file')]
    private Collection $modelesFiles;

    public function __construct()
    {
        $this->modelesFiles = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getStorage(): ?string
    {
        return $this->storage;
    }

    public function setStorage(string $storage): static
    {
        $this->storage = $storage;

        return $this;
    }

    public function getFilenameDisk(): string
    {
        return $this->filename_disk;
    }

    /**
     * @return Collection<int, ModelesFiles>
     */
    public function getModelesFiles(): Collection
    {
        return $this->modelesFiles;
    }

    public function addModelesFile(ModelesFiles $modelesFile): static
    {
        if (!$this->modelesFiles->contains($modelesFile)) {
            $this->modelesFiles->add($modelesFile);
            $modelesFile->setFile($this);
        }

        return $this;
    }

    public function removeModelesFile(ModelesFiles $modelesFile): static
    {
        if ($this->modelesFiles->removeElement($modelesFile)) {
            // set the owning side to null (unless already changed)
            if ($modelesFile->getFile() === $this) {
                $modelesFile->setFile(null);
            }
        }

        return $this;
    }
}
