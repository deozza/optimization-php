<?php

namespace App\Entity;

use App\Repository\DirectusFilesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DirectusFilesRepository::class)]
#[ORM\Table(name: "directus_files")]
#[ORM\Index(columns: ["type"], name: "type_idx")]
class DirectusFiles
{
    #[ORM\Id]
    #[ORM\Column(type: "string", length: 36)]
    private ?string $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $storage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $filename_disk = null;

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

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $created_on = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $modified_by = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $modified_on = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $charset = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $filesize = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $width = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $height = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $duration = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $embed = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tags = null;

    #[ORM\Column(type: "json", nullable: true)]
    private ?array $metadata = null;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $focal_point_x = null;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $focal_point_y = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tus_id = null;

    #[ORM\Column(type: "json", nullable: true)]
    private ?array $tus_data = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $uploaded_on = null;

    #[ORM\OneToMany(mappedBy: "directus_file", targetEntity: ModelesFiles::class)]
    private $modelesFiles;

    public function __construct()
    {
        $this->modelesFiles = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getStorage(): ?string
    {
        return $this->storage;
    }

    public function setStorage(?string $storage): static
    {
        $this->storage = $storage;

        return $this;
    }

    public function getFilenameDisk(): ?string
    {
        return $this->filename_disk;
    }

    public function setFilenameDisk(?string $filename_disk): static
    {
        $this->filename_disk = $filename_disk;

        return $this;
    }

    public function getFilenameDownload(): ?string
    {
        return $this->filename_download;
    }

    public function setFilenameDownload(?string $filename_download): static
    {
        $this->filename_download = $filename_download;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getFolder(): ?string
    {
        return $this->folder;
    }

    public function setFolder(?string $folder): static
    {
        $this->folder = $folder;

        return $this;
    }

    public function getUploadedBy(): ?string
    {
        return $this->uploaded_by;
    }

    public function setUploadedBy(?string $uploaded_by): static
    {
        $this->uploaded_by = $uploaded_by;

        return $this;
    }

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->created_on;
    }

    public function setCreatedOn(?\DateTimeInterface $created_on): static
    {
        $this->created_on = $created_on;

        return $this;
    }

    public function getCreatedOnString(): ?string
    {
        return $this->created_on ? $this->created_on->format('Y-m-d H:i:s') : null;
    }
}