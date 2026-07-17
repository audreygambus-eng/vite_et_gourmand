<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class StatistiqueCommande
{
    #[MongoDB\Id]
    private ?string $id = null;

    #[MongoDB\Field(type: 'int')]
    private int $menuId;

    #[MongoDB\Field(type: 'string')]
    private string $menuTitre;

    #[MongoDB\Field(type: 'date')]
    private \DateTime $dateCommande;

    #[MongoDB\Field(type: 'float')]
    private float $montant;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMenuId(): int
    {
        return $this->menuId;
    }

    public function setMenuId(int $menuId): static
    {
        $this->menuId = $menuId;
        return $this;
    }

    public function getMenuTitre(): string
    {
        return $this->menuTitre;
    }

    public function setMenuTitre(string $menuTitre): static
    {
        $this->menuTitre = $menuTitre;
        return $this;
    }

    public function getDateCommande(): \DateTime
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTime $dateCommande): static
    {
        $this->dateCommande = $dateCommande;
        return $this;
    }

    public function getMontant(): float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;
        return $this;
    }
}