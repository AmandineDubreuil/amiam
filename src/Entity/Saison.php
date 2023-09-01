<?php

namespace App\Entity;

use App\Repository\SaisonRepository;
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
}
