<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Le nom ne peut être vide")
     * @Assert\Length(min=2, minMessage="le nom est trop court",
     *     max=100, maxMessage="le nom est trop long")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Le prenom ne peut être vide")
     * @Assert\Length(min=2, minMessage="Le prénom est trop court",
     *     max=100, maxMessage="Le prénom est trop long")
     */
    private $firstName;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=150, unique=true, nullable=true)
     * @Assert\NotBlank(message="L'adresse e-mail ne peut être vide")
     * @Assert\Email(message="L'adresse e-mail doit être valide")
     * @Assert\Length(min=5, minMessage="L'adresse e-mail est trop court",
     *     max=150, maxMessage="L'adresse e-mail est trop long")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank(message="Le mot de passe ne peut être vide")
     * @Assert\Length(min=2, minMessage="Le mot de passe est trop court",
     *     max=200, maxMessage="Le mot de passe est trop long")
     */
    private $password;

    /**
     * @ORM\Column(type="date")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="simple_array")
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Game", inversedBy="users")
     */
    private $favorites;

    public function __construct()
    {
        $this->role = ["ROLE_USER"];
        $this->createdAt = new \DateTime();
        $this->favorites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getRoles()
    {
        return $this->role;
    }

    public function setRole(array $role): void
    {
        $this->role = $role;
    }


    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->getEmail();
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }


    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Game $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
        }

        return $this;
    }

    public function removeFavorite(Game $favorite): self
    {
        if ($this->favorites->contains($favorite)) {
            $this->favorites->removeElement($favorite);
        }

        return $this;
    }
}
