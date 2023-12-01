<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\ElementFactureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ElementFactureRepository::class)]
class ElementFacture
{
    use Timestampable;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $designation = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $valeur = null;

    #[ORM\Column]
    private ?bool $estSup = false;

    #[ORM\ManyToOne(inversedBy: 'elementFactures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Facture $facture = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $qte = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $mntTotal = null;

    public function __construct()
    {
        $this->estSup = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): static
    {
        $this->designation = $designation;

        return $this;
    }

    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setValeur(?string $valeur): static
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function isEstSup(): ?bool
    {
        return $this->estSup;
    }

    public function setEstSup(bool $estSup): static
    {
        $this->estSup = $estSup;

        return $this;
    }

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(?Facture $facture): static
    {
        $this->facture = $facture;

        return $this;
    }

    public function getQte(): ?string
    {
        return $this->qte;
    }

    public function setQte(?string $qte): static
    {
        $this->qte = $qte;

        return $this;
    }

    public function getMntTotal(): ?string
    {
        return $this->mntTotal;
    }

    public function setMntTotal(?string $mntTotal): static
    {
        $this->mntTotal = $mntTotal;

        return $this;
    }
}
