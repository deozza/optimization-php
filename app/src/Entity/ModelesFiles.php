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

    #[ORM\ManyToOne(targetEntity: Modeles::class)]
    #[ORM\JoinColumn(name: 'modeles_id', referencedColumnName: 'id', nullable: false)]
    private ?Modeles $modele = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $directus_files_id = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getModele(): ?Modeles
    {
        return $this->modele;
    }

    public function setModele(?Modeles $modele): static
    {
        $this->modele = $modele;

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
}
