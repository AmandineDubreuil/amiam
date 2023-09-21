<?php

namespace App\Entity;

use App\Repository\AllergeneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AllergeneRepository::class)]
class Allergene
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $allergene = null;

    #[ORM\ManyToMany(targetEntity: Aliment::class, mappedBy: 'allergene')]
    private Collection $aliments;

    #[ORM\ManyToMany(targetEntity: Ami::class, mappedBy: 'allergies')]
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

    public function getAllergene(): ?string
    {
        return $this->allergene;
    }

    public function setAllergene(string $allergene): static
    {
        $this->allergene = $allergene;

        return $this;
    }
    public function __toString(): string
    {
        return $this->getId();   
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
            $aliment->addAllergene($this);
        }

        return $this;
    }

    public function removeAliment(Aliment $aliment): static
    {
        if ($this->aliments->removeElement($aliment)) {
            $aliment->removeAllergene($this);
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
            $ami->addAllergy($this);
        }

        return $this;
    }

    public function removeAmi(Ami $ami): static
    {
        if ($this->amis->removeElement($ami)) {
            $ami->removeAllergy($this);
        }

        return $this;
    }
}
