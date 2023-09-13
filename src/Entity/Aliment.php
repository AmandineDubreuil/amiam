<?php

namespace App\Entity;

use App\Repository\AlimentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlimentRepository::class)]
class Aliment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $aliment = null;

    #[ORM\ManyToMany(targetEntity: Allergene::class, inversedBy: 'aliments')]
    private Collection $allergene;

    #[ORM\ManyToMany(targetEntity: Regime::class, inversedBy: 'aliments')]
    private Collection $regime;

    #[ORM\ManyToOne(inversedBy: 'aliments')]
    private ?SousGroupeAli $sousGroupe = null;

    #[ORM\ManyToMany(targetEntity: Saison::class, inversedBy: 'aliments')]
    private Collection $saison;

    #[ORM\OneToMany(mappedBy: 'aliment', targetEntity: RecetteIngredient::class)]
    private Collection $recetteIngredients;

    #[ORM\ManyToMany(targetEntity: Ami::class, mappedBy: 'degout')]
    private Collection $degoutAmis;

    #[ORM\ManyToMany(targetEntity: Ami::class, mappedBy: 'allergiesAliment')]
    private Collection $allergieAmis;

    public function __construct()
    {
        $this->allergene = new ArrayCollection();
        $this->regime = new ArrayCollection();
        $this->saison = new ArrayCollection();
        $this->recetteIngredients = new ArrayCollection();
        $this->degoutAmis = new ArrayCollection();
        $this->allergieAmis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAliment(): ?string
    {
        return $this->aliment;
    }

    public function setAliment(string $aliment): static
    {
        $this->aliment = $aliment;

        return $this;
    }

    /**
     * @return Collection<int, Allergene>
     */
    public function getAllergene(): Collection
    {
        return $this->allergene;
    }

    public function addAllergene(Allergene $allergene): static
    {
        if (!$this->allergene->contains($allergene)) {
            $this->allergene->add($allergene);
        }

        return $this;
    }

    public function removeAllergene(Allergene $allergene): static
    {
        $this->allergene->removeElement($allergene);

        return $this;
    }

    /**
     * @return Collection<int, Regime>
     */
    public function getRegime(): Collection
    {
        return $this->regime;
    }

    public function addRegime(Regime $regime): static
    {
        if (!$this->regime->contains($regime)) {
            $this->regime->add($regime);
        }

        return $this;
    }

    public function removeRegime(Regime $regime): static
    {
        $this->regime->removeElement($regime);

        return $this;
    }

    public function getSousGroupe(): ?SousGroupeAli
    {
        return $this->sousGroupe;
    }

    public function setSousGroupe(?SousGroupeAli $sousGroupe): static
    {
        $this->sousGroupe = $sousGroupe;

        return $this;
    }
    public function __toString(): string
    {
        return $this->getAliment();   
    }
    /**
     * @return Collection<int, Saison>
     */
    public function getSaison(): Collection
    {
        return $this->saison;
    }

    public function addSaison(Saison $saison): static
    {
        if (!$this->saison->contains($saison)) {
            $this->saison->add($saison);
        }

        return $this;
    }

    public function removeSaison(Saison $saison): static
    {
        $this->saison->removeElement($saison);

        return $this;
    }

    /**
     * @return Collection<int, RecetteIngredient>
     */
    public function getRecetteIngredients(): Collection
    {
        return $this->recetteIngredients;
    }

    public function addRecetteIngredient(RecetteIngredient $recetteIngredient): static
    {
        if (!$this->recetteIngredients->contains($recetteIngredient)) {
            $this->recetteIngredients->add($recetteIngredient);
            $recetteIngredient->setAliment($this);
        }

        return $this;
    }

    public function removeRecetteIngredient(RecetteIngredient $recetteIngredient): static
    {
        if ($this->recetteIngredients->removeElement($recetteIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recetteIngredient->getAliment() === $this) {
                $recetteIngredient->setAliment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ami>
     */
    public function getDegoutAmis(): Collection
    {
        return $this->degoutAmis;
    }

    public function addDegoutAmi(Ami $degoutAmi): static
    {
        if (!$this->degoutAmis->contains($degoutAmi)) {
            $this->degoutAmis->add($degoutAmi);
            $degoutAmi->addDegout($this);
        }

        return $this;
    }

    public function removeDegoutAmi(Ami $degoutAmi): static
    {
        if ($this->degoutAmis->removeElement($degoutAmi)) {
            $degoutAmi->removeDegout($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Ami>
     */
    public function getAllergieAmis(): Collection
    {
        return $this->allergieAmis;
    }

    public function addAllergieAmis(Ami $allergieAmis): static
    {
        if (!$this->allergieAmis->contains($allergieAmis)) {
            $this->allergieAmis->add($allergieAmis);
            $allergieAmis->addAllergiesAliment($this);
        }

        return $this;
    }

    public function removeAllergieAmis(Ami $allergieAmis): static
    {
        if ($this->allergieAmis->removeElement($allergieAmis)) {
            $allergieAmis->removeAllergiesAliment($this);
        }

        return $this;
    }
}
