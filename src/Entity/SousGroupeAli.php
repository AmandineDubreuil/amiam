<?php

namespace App\Entity;

use App\Repository\SousGroupeAliRepository;
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
}
