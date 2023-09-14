<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AmiFamilleRepository;

#[ORM\Entity(repositoryClass: AmiFamilleRepository::class)]
class AmiFamille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\ManyToOne(inversedBy: 'amiFamilles')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'famille', targetEntity: Ami::class)]
    private Collection $amis;

    #[ORM\ManyToMany(targetEntity: Repas::class, mappedBy: 'amis')]
    private Collection $repas;

    public function __construct()
    {
        $this->amis = new ArrayCollection();
        $this->repas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
    public function __toString(): string
    {
        return $this->getId();   
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
            $ami->setFamille($this);
        }

        return $this;
    }

    public function removeAmi(Ami $ami): static
    {
        if ($this->amis->removeElement($ami)) {
            // set the owning side to null (unless already changed)
            if ($ami->getFamille() === $this) {
                $ami->setFamille(null);
            }
        }

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
}
