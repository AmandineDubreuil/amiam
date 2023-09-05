<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[ORM\Column]
    private ?float $nbPersonnes = null;

    #[ORM\Column(nullable: true)]
    private ?int $tpsPreparation = null;

    #[ORM\Column(nullable: true)]
    private ?int $tpsCuisson = null;

    #[ORM\Column(nullable: true)]
    private ?int $tpsRepos = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $video = null;

    #[ORM\ManyToOne(inversedBy: 'recettes')]
    private ?User $user = null;

    #[ORM\Column]
    private ?bool $prive = null;

    #[ORM\ManyToOne(inversedBy: 'recettes')]
    private ?RecetteCategorie $categorie = null;

    #[ORM\OneToMany(mappedBy: 'recette', targetEntity: RecetteIngredient::class)]
    private Collection $ingredients;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
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

    public function getNbPersonnes(): ?float
    {
        return $this->nbPersonnes;
    }

    public function setNbPersonnes(float $nbPersonnes): static
    {
        $this->nbPersonnes = $nbPersonnes;

        return $this;
    }

    public function getTpsPreparation(): ?int
    {
        return $this->tpsPreparation;
    }

    public function setTpsPreparation(?int $tpsPreparation): static
    {
        $this->tpsPreparation = $tpsPreparation;

        return $this;
    }

    public function getTpsCuisson(): ?int
    {
        return $this->tpsCuisson;
    }

    public function setTpsCuisson(?int $tpsCuisson): static
    {
        $this->tpsCuisson = $tpsCuisson;

        return $this;
    }

    public function getTpsRepos(): ?int
    {
        return $this->tpsRepos;
    }

    public function setTpsRepos(?int $tpsRepos): static
    {
        $this->tpsRepos = $tpsRepos;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): static
    {
        $this->video = $video;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function isPrive(): ?bool
    {
        return $this->prive;
    }

    public function setPrive(bool $prive): static
    {
        $this->prive = $prive;

        return $this;
    }

    public function getCategorie(): ?RecetteCategorie
    {
        return $this->categorie;
    }

    public function setCategorie(?RecetteCategorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }
    public function __toString(): string
    {
        return $this->getId();   
    }
    /**
     * @return Collection<int, RecetteIngredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(RecetteIngredient $ingredient): static
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
            $ingredient->setRecette($this);
        }

        return $this;
    }

    public function removeIngredient(RecetteIngredient $ingredient): static
    {
        if ($this->ingredients->removeElement($ingredient)) {
            // set the owning side to null (unless already changed)
            if ($ingredient->getRecette() === $this) {
                $ingredient->setRecette(null);
            }
        }

        return $this;
    }
}
