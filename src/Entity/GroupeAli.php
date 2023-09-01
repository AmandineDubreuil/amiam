<?php

namespace App\Entity;

use App\Repository\GroupeAliRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupeAliRepository::class)]
class GroupeAli
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $groupe = null;

    #[ORM\OneToMany(mappedBy: 'groupe', targetEntity: SousGroupeAli::class)]
    private Collection $sousGroupeAlis;

    public function __construct()
    {
        $this->sousGroupeAlis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupe(): ?string
    {
        return $this->groupe;
    }

    public function setGroupe(string $groupe): static
    {
        $this->groupe = $groupe;

        return $this;
    }

    
    public function __toString(): string
    {
        return $this->getGroupe();   
    }

    /**
     * @return Collection<int, SousGroupeAli>
     */
    public function getSousGroupeAlis(): Collection
    {
        return $this->sousGroupeAlis;
    }

    public function addSousGroupeAli(SousGroupeAli $sousGroupeAli): static
    {
        if (!$this->sousGroupeAlis->contains($sousGroupeAli)) {
            $this->sousGroupeAlis->add($sousGroupeAli);
            $sousGroupeAli->setGroupe($this);
        }

        return $this;
    }

    public function removeSousGroupeAli(SousGroupeAli $sousGroupeAli): static
    {
        if ($this->sousGroupeAlis->removeElement($sousGroupeAli)) {
            // set the owning side to null (unless already changed)
            if ($sousGroupeAli->getGroupe() === $this) {
                $sousGroupeAli->setGroupe(null);
            }
        }

        return $this;
    }
}
