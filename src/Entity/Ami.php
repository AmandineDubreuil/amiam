<?php

namespace App\Entity;

use App\Repository\AmiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AmiRepository::class)]
class Ami
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\ManyToMany(targetEntity: Regime::class, inversedBy: 'amis')]
    private Collection $regimes;

    #[ORM\ManyToMany(targetEntity: Allergene::class, inversedBy: 'amis')]
    private Collection $allergies;

    #[ORM\ManyToMany(targetEntity: Aliment::class, inversedBy: 'degoutAmis')]
    private Collection $degout;

    #[ORM\ManyToOne(inversedBy: 'amis')]
    private ?AmiFamille $famille = null;

    public function __construct()
    {
        $this->regimes = new ArrayCollection();
        $this->allergies = new ArrayCollection();
        $this->degout = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * @return Collection<int, Regime>
     */
    public function getRegimes(): Collection
    {
        return $this->regimes;
    }

    public function addRegime(Regime $regime): static
    {
        if (!$this->regimes->contains($regime)) {
            $this->regimes->add($regime);
        }

        return $this;
    }

    public function removeRegime(Regime $regime): static
    {
        $this->regimes->removeElement($regime);

        return $this;
    }

    /**
     * @return Collection<int, Allergene>
     */
    public function getAllergies(): Collection
    {
        return $this->allergies;
    }

    public function addAllergy(Allergene $allergy): static
    {
        if (!$this->allergies->contains($allergy)) {
            $this->allergies->add($allergy);
        }

        return $this;
    }

    public function removeAllergy(Allergene $allergy): static
    {
        $this->allergies->removeElement($allergy);

        return $this;
    }

    /**
     * @return Collection<int, Aliment>
     */
    public function getDegout(): Collection
    {
        return $this->degout;
    }

    public function addDegout(Aliment $degout): static
    {
        if (!$this->degout->contains($degout)) {
            $this->degout->add($degout);
        }

        return $this;
    }

    public function removeDegout(Aliment $degout): static
    {
        $this->degout->removeElement($degout);

        return $this;
    }

    public function getFamille(): ?AmiFamille
    {
        return $this->famille;
    }

    public function setFamille(?AmiFamille $famille): static
    {
        $this->famille = $famille;

        return $this;
    }
}
