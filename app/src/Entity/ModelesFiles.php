<?php

namespace App\Entity;

use App\Repository\ModelesFilesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModelesFilesRepository::class)]
#[ORM\Table(name: "modeles_files")]
#[ORM\Index(columns: ["modeles_id"], name: "modeles_idx")]
#[ORM\Index(columns: ["directus_files_id"], name: "files_idx")]
class ModelesFiles
{
    #[ORM\Id]
    #[ORM\Column(type: "string", length: 36)]
    private ?string $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $modeles_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $directus_files_id = null;

    #[ORM\ManyToOne(targetEntity: Modeles::class, inversedBy: "files")]
    #[ORM\JoinColumn(name: "modeles_id", referencedColumnName: "id")]
    private ?Modeles $modele = null;

    #[ORM\ManyToOne(targetEntity: DirectusFiles::class, inversedBy: "modelesFiles")]
    #[ORM\JoinColumn(name: "directus_files_id", referencedColumnName: "id")]
    private ?DirectusFiles $directus_file = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;
        
        return $this;
    }

    public function getModelesId(): ?string
    {
        return $this->modeles_id;
    }

    public function setModelesId(?string $modeles_id): static
    {
        $this->modeles_id = $modeles_id;

        return $this;
    }

    public function getDirectusFilesId(): ?string
    {
        return $this->directus_files_id;
    }

    public function setDirectusFilesId(?string $directus_files_id): static
    {
        $this->directus_files_id = $directus_files_id;

        return $this;
    }

    public function getModele(): ?Modeles
    {
        return $this->modele;
    }

    public function setModele(?Modeles $modele): static
    {
        $this->modele = $modele;
        $this->modeles_id = $modele?->getId();

        return $this;
    }

    public function getDirectusFile(): ?DirectusFiles
    {
        return $this->directus_file;
    }

    public function setDirectusFile(?DirectusFiles $directus_file): static
    {
        $this->directus_file = $directus_file;
        $this->directus_files_id = $directus_file?->getId();

        return $this;
    }
}