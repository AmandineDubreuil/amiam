<?php

namespace App\Entity;

use App\Repository\RepasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RepasRepository::class)]
class Repas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: amiFamille::class, inversedBy: 'repas')]
    private Collection $amis;

    #[ORM\ManyToOne(inversedBy: 'repas')]
    private ?recette $recettes = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaire = null;

    public function __construct()
    {
        $this->amis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, amiFamille>
     */
    public function getAmis(): Collection
    {
        return $this->amis;
    }

    public function addAmi(amiFamille $ami): static
    {
        if (!$this->amis->contains($ami)) {
            $this->amis->add($ami);
        }

        return $this;
    }

    public function removeAmi(amiFamille $ami): static
    {
        $this->amis->removeElement($ami);

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
}