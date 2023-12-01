<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\PaiementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
class Paiement
{
    use Timestampable;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $refPai = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datePaie = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2)]
    private ?string $montantPaie = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2)]
    private ?string $restAPayer = null;

    #[ORM\ManyToOne(inversedBy: 'paiements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Facture $facture = null;

    #[ORM\ManyToOne(inversedBy: 'paiements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ModePaiement $modePaiement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefPai(): ?string
    {
        return $this->refPai;
    }

    public function setRefPai(string $refPai): static
    {
        $this->refPai = $refPai;

        return $this;
    }

    public function getDatePaie(): ?\DateTimeInterface
    {
        return $this->datePaie;
    }

    public function setDatePaie(\DateTimeInterface $datePaie): static
    {
        $this->datePaie = $datePaie;

        return $this;
    }

    public function getMontantPaie(): ?string
    {
        return $this->montantPaie;
    }

    public function setMontantPaie(string $montantPaie): static
    {
        $this->montantPaie = $montantPaie;

        return $this;
    }

    public function getRestAPayer(): ?string
    {
        return $this->restAPayer;
    }

    public function setRestAPayer(string $restAPayer): static
    {
        $this->restAPayer = $restAPayer;

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

    public function getModePaiement(): ?ModePaiement
    {
        return $this->modePaiement;
    }

    public function setModePaiement(?ModePaiement $modePaiement): static
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }
}
