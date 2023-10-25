<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AmiRepository;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

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
    #[ORM\JoinColumn(name:"famille_id", onDelete:"CASCADE")]
    private ?AmiFamille $famille = null;

    #[ORM\ManyToMany(targetEntity: Aliment::class, inversedBy: 'amis')]
    #[JoinTable(name: 'ami_aliment_allergie')]
    private Collection $allergiesAliment;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\ManyToMany(targetEntity: Repas::class, mappedBy: 'amis')]
    private Collection $repas;

    #[ORM\ManyToMany(targetEntity: SousGroupeAli::class, inversedBy: 'amis')]
    private Collection $degoutGroupeAli;

    public function __construct()
    {
        $this->regimes = new ArrayCollection();
        $this->allergies = new ArrayCollection();
        $this->degout = new ArrayCollection();
        $this->allergiesAliment = new ArrayCollection();
        $this->repas = new ArrayCollection();
        $this->degoutGroupeAli = new ArrayCollection();
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

    public function getAge()
    {
        $dateInterval = $this->dateNaissance->diff(new \DateTime());
 
        return $dateInterval->y;
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

    /**
     * @return Collection<int, Aliment>
     */
    public function getAllergiesAliment(): Collection
    {
        return $this->allergiesAliment;
    }

    public function addAllergiesAliment(Aliment $allergiesAliment): static
    {
        if (!$this->allergiesAliment->contains($allergiesAliment)) {
            $this->allergiesAliment->add($allergiesAliment);
        }

        return $this;
    }

    public function removeAllergiesAliment(Aliment $allergiesAliment): static
    {
        $this->allergiesAliment->removeElement($allergiesAliment);

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection<int, Repas>
     */
    public function getRepas(): Collection
    {
        return $this->repas;
    }

    public function addRepa(Repas $repa): static
    {
        if (!$this->repas->contains($repa)) {
            $this->repas->add($repa);
            $repa->addAmi($this);
        }

        return $this;
    }

    public function removeRepa(Repas $repa): static
    {
        if ($this->repas->removeElement($repa)) {
            $repa->removeAmi($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, SousGroupeAli>
     */
    public function getDegoutGroupeAli(): Collection
    {
        return $this->degoutGroupeAli;
    }

    public function addDegoutGroupeAli(SousGroupeAli $degoutGroupeAli): static
    {
        if (!$this->degoutGroupeAli->contains($degoutGroupeAli)) {
            $this->degoutGroupeAli->add($degoutGroupeAli);
        }

        return $this;
    }

    public function removeDegoutGroupeAli(SousGroupeAli $degoutGroupeAli): static
    {
        $this->degoutGroupeAli->removeElement($degoutGroupeAli);

        return $this;
    }
}
