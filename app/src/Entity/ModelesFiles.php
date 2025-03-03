<?php

namespace App\Entity;

use App\Repository\ModelesFilesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModelesFilesRepository::class)]
class ModelesFiles
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $modeles_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $directus_files_id = null;

    #[ORM\ManyToOne(inversedBy: 'files')]
    private ?Modeles $modeles = null;

    #[ORM\ManyToOne(inversedBy: 'modelesFiles')]
    #[ORM\JoinColumn(name: 'directus_files_id', referencedColumnName: 'id')]
    private ?DirectusFiles $file = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getModelesId(): ?string
    {
        return $this->modeles_id;
    }

    public function setModelesId(string $modeles_id): static
    {
        $this->modeles_id = $modeles_id;

        return $this;
    }

    public function getDirectusFilesId(): ?string
    {
        return $this->directus_files_id;
    }

    public function setDirectusFilesId(string $directus_files_id): static
    {
        $this->directus_files_id = $directus_files_id;

        return $this;
    }

    public function getModeles(): ?Modeles
    {
        return $this->modeles;
    }

    public function setModeles(?Modeles $modeles): static
    {
        $this->modeles = $modeles;

        return $this;
    }

    public function getFile(): ?DirectusFiles
    {
        return $this->file;
    }

    public function setFile(?DirectusFiles $file): static
    {
        $this->file = $file;

        return $this;
    }
}
