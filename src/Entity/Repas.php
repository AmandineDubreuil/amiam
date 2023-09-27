<?php

namespace App\Entity;

use App\Entity\AmiFamille;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RepasRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: RepasRepository::class)]
class Repas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: AmiFamille::class, inversedBy: 'repas')]
    private Collection $amiFamilles;

    #[ORM\ManyToOne(inversedBy: 'repas')]
    private ?Recette $recettes = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'repas')]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Ami::class, inversedBy: 'repas')]
    private Collection $amis;

    public function __construct()
    {
        $this->amiFamilles = new ArrayCollection();
        $this->amis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, amiFamille>
     */
    public function getAmiFamilles(): Collection
    {
        return $this->amiFamilles;
    }

    public function addAmiFamille(AmiFamille $amiFamille): static
    {
        if (!$this->amiFamilles->contains($amiFamille)) {
            $this->amiFamilles->add($amiFamille);
        }

        return $this;
    }

    public function removeAmiFamille(AmiFamille $amiFamille): static
    {
        $this->amiFamilles->removeElement($amiFamille);

        return $this;
    }

    public function getRecettes(): ?recette
    {
        return $this->recettes;
    }

    public function setRecettes(?recette $recettes): static
    {
        $this->recettes = $recettes;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

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
        }

        return $this;
    }

    public function removeAmi(Ami $ami): static
    {
        $this->amis->removeElement($ami);

        return $this;
    }
}
