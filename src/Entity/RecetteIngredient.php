<?php

namespace App\Entity;

use App\Repository\RecetteIngredientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecetteIngredientRepository::class)]
class RecetteIngredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'recetteIngredients')]
    private ?Aliment $aliment = null;

    #[ORM\Column]
    private ?float $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'recetteIngredients')]
    private ?Mesure $mesure = null;

    #[ORM\ManyToOne(inversedBy: 'ingredients')]
    private ?Recette $recette = null;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAliment(): ?Aliment
    {
        return $this->aliment;
    }

    public function setAliment(?Aliment $aliment): static
    {
        $this->aliment = $aliment;

        return $this;
    }

    public function getQuantite(): ?float
    {
        return $this->quantite;
    }

    public function setQuantite(float $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getMesure(): ?Mesure
    {
        return $this->mesure;
    }

    public function setMesure(?Mesure $mesure): static
    {
        $this->mesure = $mesure;

        return $this;
    }

    public function getRecette(): ?Recette
    {
        return $this->recette;
    }

    public function setRecette(?Recette $recette): static
    {
        $this->recette = $recette;

        return $this;
    }
    public function __toString(): string
    {
        return $this->getId();   
    }
}
