<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], 
message: 'Un compte existe déjà avec cette adresse e-mail.',
)]
#[UniqueEntity(fields:['pseudo'],
message:'Ce pseudo est déjà utilisé, merci d\'en choisir un autre.'
)]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $modifiedAt = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserFamily::class)]
    private Collection $userFamilies;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Recette::class)]
    private Collection $recettes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: AmiFamille::class)]
    private Collection $amiFamilles;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Repas::class)]
    private Collection $repas;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Aliment::class)]
    private Collection $aliments;

    public function __construct()
    {
        $this->userFamilies = new ArrayCollection();
        $this->recettes = new ArrayCollection();
        $this->amiFamilles = new ArrayCollection();
        $this->repas = new ArrayCollection();
        $this->aliments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('pseudo', new Length([
            'min' => 2,
            'max' => 50,
            'minMessage' => 'Votre pseudo doit contenir au moins {{ limit }} caractères.',
            'maxMessage' => 'Votre pseudo ne doit pas contenir plus de {{ limit }} caractères.',
        ]));
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeImmutable
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(\DateTimeImmutable $modifiedAt): static
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function getIsVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getPseudo();   
    }


    /**
     * @return Collection<int, UserFamily>
     */
    public function getUserFamilies(): Collection
    {
        return $this->userFamilies;
    }

    public function addUserFamily(UserFamily $userFamily): static
    {
        if (!$this->userFamilies->contains($userFamily)) {
            $this->userFamilies->add($userFamily);
            $userFamily->setUser($this);
        }

        return $this;
    }

    public function removeUserFamily(UserFamily $userFamily): static
    {
        if ($this->userFamilies->removeElement($userFamily)) {
            // set the owning side to null (unless already changed)
            if ($userFamily->getUser() === $this) {
                $userFamily->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recette>
     */
    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    public function addRecette(Recette $recette): static
    {
        if (!$this->recettes->contains($recette)) {
            $this->recettes->add($recette);
            $recette->setUser($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): static
    {
        if ($this->recettes->removeElement($recette)) {
            // set the owning side to null (unless already changed)
            if ($recette->getUser() === $this) {
                $recette->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AmiFamille>
     */
    public function getAmiFamilles(): Collection
    {
        return $this->amiFamilles;
    }

    public function addAmiFamille(AmiFamille $amiFamille): static
    {
        if (!$this->amiFamilles->contains($amiFamille)) {
            $this->amiFamilles->add($amiFamille);
            $amiFamille->setUser($this);
        }

        return $this;
    }

    public function removeAmiFamille(AmiFamille $amiFamille): static
    {
        if ($this->amiFamilles->removeElement($amiFamille)) {
            // set the owning side to null (unless already changed)
            if ($amiFamille->getUser() === $this) {
                $amiFamille->setUser(null);
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
            $repa->setUser($this);
        }

        return $this;
    }

    public function removeRepa(Repas $repa): static
    {
        if ($this->repas->removeElement($repa)) {
            // set the owning side to null (unless already changed)
            if ($repa->getUser() === $this) {
                $repa->setUser(null);
            }
        }

        return $this;
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
            $aliment->setUser($this);
        }

        return $this;
    }

    public function removeAliment(Aliment $aliment): static
    {
        if ($this->aliments->removeElement($aliment)) {
            // set the owning side to null (unless already changed)
            if ($aliment->getUser() === $this) {
                $aliment->setUser(null);
            }
        }

        return $this;
    }
}
