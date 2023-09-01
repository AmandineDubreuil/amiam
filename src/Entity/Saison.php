<?php

namespace App\Entity;

use App\Repository\SaisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SaisonRepository::class)]
class Saison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $moisL = null;

    #[ORM\Column]
    private ?int $moisC = null;

    #[ORM\ManyToMany(targetEntity: Aliment::class, mappedBy: 'saison')]
    private Collection $aliments;

    public function __construct()
    {
        $this->aliments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMoisL(): ?string
    {
        return $this->moisL;
    }

    public function setMoisL(string $moisL): static
    {
        $this->moisL = $moisL;

        return $this;
    }

    public function getMoisC(): ?int
    {
        return $this->moisC;
    }

    public function setMoisC(int $moisC): static
    {
        $this->moisC = $moisC;

        return $this;
    }

    /**
     * @return Collection<int, Aliment>
     */
    public function getAliments(): Collection
    {
        return $this->aliments;
    }

    public function addAliment(Aliment $aliment): static
    {
        if (!$this->aliments->contains($aliment)) {
            $this->aliments->add($aliment);
            $aliment->addSaison($this);
        }

        return $this;
    }

    public function removeAliment(Aliment $aliment): static
    {
        if ($this->aliments->removeElement($aliment)) {
            $aliment->removeSaison($this);
        }

        return $this;
    }
}
