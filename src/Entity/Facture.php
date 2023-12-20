<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    use Timestampable;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $refFact = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateFac = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2)]
    private ?string $montantFac = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 30, scale: 2, nullable: true)]
    private ?string $montantRest = null;

    #[ORM\OneToMany(mappedBy: 'facture', targetEntity: Paiement::class)]
    private Collection $paiements;

    #[ORM\OneToMany(mappedBy: 'facture', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\OneToMany(mappedBy: 'facture', targetEntity: ElementFacture::class, orphanRemoval: true, cascade:["remove","persist"])]
    private Collection $elementFactures;

    #[ORM\Column(length: 12)]
    private ?string $statut = null;

    #[ORM\Column]
    private ?bool $estSup = null;

    #[ORM\Column]
    private ?bool $estValide = null;

    #[ORM\Column(length: 255)]
    private ?string $emetteur = null;

    #[ORM\Column]
    private ?int $acteur = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $motif = null;

    public function __construct()
    {
        $this->paiements = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->elementFactures = new ArrayCollection();
        $this->statut = "En cours";
        $this->estValide = 0;
        $this->estSup = 0;
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefFact(): ?string
    {
        return $this->refFact;
    }

    public function setRefFact(string $refFact): static
    {
        $this->refFact = $refFact;

        return $this;
    }

    public function getDateFac(): ?\DateTimeInterface
    {
        return $this->dateFac;
    }

    public function setDateFac(\DateTimeInterface $dateFac): static
    {
        $this->dateFac = $dateFac;

        return $this;
    }

    public function getMontantFac(): ?string
    {
        return $this->montantFac;
    }

    public function setMontantFac(string $montantFac): static
    {
        $this->montantFac = $montantFac;

        return $this;
    }

    public function getMontantRest(): ?string
    {
        return $this->montantRest;
    }

    public function setMontantRest(?string $montantRest): static
    {
        $this->montantRest = $montantRest;

        return $this;
    }

    /**
     * @return Collection<int, Paiement>
     */
    public function getPaiements(): Collection
    {
        return $this->paiements;
    }

    public function addPaiement(Paiement $paiement): static
    {
        if (!$this->paiements->contains($paiement)) {
            $this->paiements->add($paiement);
            $paiement->setFacture($this);
        }

        return $this;
    }

    public function removePaiement(Paiement $paiement): static
    {
        if ($this->paiements->removeElement($paiement)) {
            // set the owning side to null (unless already changed)
            if ($paiement->getFacture() === $this) {
                $paiement->setFacture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setFacture($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getFacture() === $this) {
                $commentaire->setFacture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ElementFacture>
     */
    public function getElementFactures(): Collection
    {
        return $this->elementFactures;
    }

    public function addElementFacture(ElementFacture $elementFacture): static
    {
        if (!$this->elementFactures->contains($elementFacture)) {
            $this->elementFactures->add($elementFacture);
            $elementFacture->setFacture($this);
        }

        return $this;
    }

    public function removeElementFacture(ElementFacture $elementFacture): static
    {
        if ($this->elementFactures->removeElement($elementFacture)) {
            // set the owning side to null (unless already changed)
            if ($elementFacture->getFacture() === $this) {
                $elementFacture->setFacture(null);
            }
        }

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

    public function isEstSup(): ?bool
    {
        return $this->estSup;
    }

    public function setEstSup(bool $estSup): static
    {
        $this->estSup = $estSup;

        return $this;
    }

    public function isEstValide(): ?bool
    {
        return $this->estValide;
    }

    public function setEstValide(bool $estValide): static
    {
        $this->estValide = $estValide;

        return $this;
    }

    public function getEmetteur(): ?string
    {
        return $this->emetteur;
    }

    public function setEmetteur(string $emetteur): static
    {
        $this->emetteur = $emetteur;

        return $this;
    }

    public function __toString()
    {
       return $this->refFact. " [ ".$this->emetteur."]";
    }

    public function getActeur(): ?int
    {
        return $this->acteur;
    }

    public function setActeur(int $acteur): static
    {
        $this->acteur = $acteur;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(?string $motif): static
    {
        $this->motif = $motif;

        return $this;
    }
}
