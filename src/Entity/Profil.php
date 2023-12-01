<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\ProfilRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfilRepository::class)]
class Profil
{
    use Timestampable;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(nullable: true)]
    private ?array $roles = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estSup = null;

    public function __construct()
    {
        $this->estSup = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function isEstSup(): ?bool
    {
        return $this->estSup;
    }

    public function setEstSup(?bool $estSup): static
    {
        $this->estSup = $estSup;

        return $this;
    }
}
