<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    use Timestampable;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sousTitre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titre = null;

    #[ORM\Column(length: 4000, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    #[ORM\ManyToOne(targetEntity: self::class,inversedBy: 'sousMenu')]
    private ?self $menuSuperieur = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $icon = null;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $typeMenu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $roles = null;

    #[ORM\OneToMany(mappedBy: 'menuSuperieur', targetEntity: Menu::class, cascade:["remove","persist"])]
    private Collection $sousMenus;

    public function __construct()
    {
        $this->sousMenus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSousTitre(): ?string
    {
        return $this->sousTitre;
    }

    public function setSousTitre(?string $sousTitre): static
    {
        $this->sousTitre = $sousTitre;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getMenuSuperieur(): ?self
    {
        return $this->menuSuperieur;
    }

    public function setMenuSuperieur(?self $menuSuperieur): static
    {
        $this->menuSuperieur = $menuSuperieur;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getTypeMenu(): ?string
    {
        return $this->typeMenu;
    }

    public function setTypeMenu(string $typeMenu): static
    {
        $this->typeMenu = $typeMenu;

        return $this;
    }

    public function getRoles(): ?string
    {
        return $this->roles;
    }

    public function setRoles(?string $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getSousMenu(): Collection
    {
        return $this->sousMenus;
    }

    public function addSousMenu(Menu $sousMenu): static
    {
        if (!$this->sousMenus->contains($sousMenu)) {
            $this->sousMenus->add($sousMenu);
            $sousMenu->setMenuSuperieur($this);
        }

        return $this;
    }

    public function removeSousMenu(Menu $sousMenu): static
    {
        if ($this->sousMenus->removeElement($sousMenu)) {
            // set the owning side to null (unless already changed)
            if ($sousMenu->getMenuSuperieur() === $this) {
                $sousMenu->setMenuSuperieur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getSousMenus(): Collection
    {
        return $this->sousMenus;
    }
}
