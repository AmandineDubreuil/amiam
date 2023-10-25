<?php

namespace App\Entity;

use App\Repository\SousGroupeAliRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SousGroupeAliRepository::class)]
class SousGroupeAli
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $sousGroupe = null;

    #[ORM\ManyToOne(inversedBy: 'sousGroupeAlis')]
    private ?GroupeAli $groupe = null;

    #[ORM\OneToMany(mappedBy: 'sousGroupe', targetEntity: Aliment::class)]
    private Collection $aliments;

    #[ORM\ManyToMany(targetEntity: Ami::class, mappedBy: 'degoutGroupeAli')]
    private Collection $amis;

    public function __construct()
    {
        $this->aliments = new ArrayCollection();
        $this->amis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSousGroupe(): ?string
    {
        return $this->sousGroupe;
    }

    public function setSousGroupe(string $sousGroupe): static
    {
        $this->sousGroupe = $sousGroupe;

        return $this;
    }

    public function getGroupe(): ?GroupeAli
    {
        return $this->groupe;
    }

    public function setGroupe(?GroupeAli $groupe): static
    {
        $this->groupe = $groupe;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getSousGroupe();   
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
            $aliment->setSousGroupe($this);
        }

        return $this;
    }

    public function removeAliment(Aliment $aliment): static
    {
        if ($this->aliments->removeElement($aliment)) {
            // set the owning side to null (unless already changed)
            if ($aliment->getSousGroupe() === $this) {
                $aliment->setSousGroupe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ami>
     */
    public function getAmis(): Collection
    {
        return $this->amis;
    }

    public function addAmi(Ami $ami): static
    {
        if (!$this->amis->contains($ami)) {
            $this->amis->add($ami);
            $ami->addDegoutGroupeAli($this);
        }

        return $this;
    }

    public function removeAmi(Ami $ami): static
    {
        if ($this->amis->removeElement($ami)) {
            $ami->removeDegoutGroupeAli($this);
        }

        return $this;
    }
}
