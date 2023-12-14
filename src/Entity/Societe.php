<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestampable;
use DateTime;
use App\Repository\SocieteRepository;
#[ORM\Entity(repositoryClass: SocieteRepository::class)]


class Societe
{
    use Timestampable;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sigle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $raisonSocial = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statut = null;

    #[ORM\Column]
    private ?string $ifu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rccm = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estSup = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $apiToken = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $apiNim = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $messageCommercial = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estModeMecef = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estRegimeTPS = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estAdmin = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estModeDeConnexion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $apiLink = null;

    // private ?\DateTimeInterface $createdAt = null;

    // private ?\DateTimeInterface $updatedAt = null;

    // private ?\DateTimeInterface $deletedAt = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Media $logo = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Media $entete = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Media $piedDePage = null;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(string $sigle): static
    {
        $this->sigle = $sigle;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getIfu(): ?string
    {
        return $this->ifu;
    }

    public function setIfu(string $ifu): static
    {
        $this->ifu = $ifu;

        return $this;
    }

    public function getRccm(): ?string
    {
        return $this->rccm;
    }

    public function setRccm(string $rccm): static
    {
        $this->rccm = $rccm;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): static
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    public function getApiNim(): ?string
    {
        return $this->apiNim;
    }

    public function setApiNim(?string $apiNim): static
    {
        $this->apiNim = $apiNim;

        return $this;
    }

    public function getMessageCommercial(): ?string
    {
        return $this->messageCommercial;
    }

    public function setMessageCommercial(?string $messageCommercial): static
    {
        $this->messageCommercial = $messageCommercial;

        return $this;
    }

    public function isEstModeMecef(): ?bool
    {
        return $this->estModeMecef;
    }

    public function setEstModeMecef(?bool $estModeMecef): static
    {
        $this->estModeMecef = $estModeMecef;

        return $this;
    }

    public function isEstRegimeTPS(): ?bool
    {
        return $this->estRegimeTPS;
    }

    public function setEstRegimeTPS(?bool $estRegimeTPS): static
    {
        $this->estRegimeTPS = $estRegimeTPS;

        return $this;
    }

    public function getApiLink(): ?string
    {
        return $this->apiLink;
    }

    public function setApiLink(?string $apiLink): static
    {
        $this->apiLink = $apiLink;

        return $this;
    }

    public function __toString()
    {
        return (String)$this->nom;
    }

    public function setEstSup(?bool $estSup): static
    {
        $this->estSup = $estSup;

        return $this;
    }

    public function getEstSup(): ?string
    {
        return $this->estSup;
    }

    public function getEstModeMecef(): ?string
    {
        return $this->estModeMecef;
    }

    public function getEstRegimeTPS(): ?string
    {
        return $this->estRegimeTPS;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function isEstSup(): ?bool
    {
        return $this->estSup;
    }

    public function getLogo(): ?Media
    {
        return $this->logo;
    }

    public function setLogo(?Media $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getEntete(): ?Media
    {
        return $this->entete;
    }

    public function setEntete(?Media $entete): static
    {
        $this->entete = $entete;

        return $this;
    }

    public function getPiedDePage(): ?Media
    {
        return $this->piedDePage;
    }

    public function setPiedDePage(?Media $piedDePage): static
    {
        $this->piedDePage = $piedDePage;

        return $this;
    }

    public function isEstModeDeConnexion(): ?bool
    {
        return $this->estModeDeConnexion;
    }

    public function setEstModeDeConnexion(?bool $estModeDeConnexion): static
    {
        $this->estModeDeConnexion = $estModeDeConnexion;

        return $this;
    }

    public function isEstAdmin(): ?bool
    {
        return $this->estAdmin;
    }

    public function setEstAdmin(?bool $estAdmin): static
    {
        $this->estAdmin = $estAdmin;

        return $this;
    }

    public function getRaisonSocial(): ?string
    {
        return $this->raisonSocial;
    }

    public function setRaisonSocial(?string $raisonSocial): static
    {
        $this->raisonSocial = $raisonSocial;

        return $this;
    }
    
}
