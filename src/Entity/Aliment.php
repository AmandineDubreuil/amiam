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

    public function __construct()
    {
        $this->allergene = new ArrayCollection();
        $this->regime = new ArrayCollection();
        $this->saison = new ArrayCollection();
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
}
