<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlatformRepository")
 */
class Platform
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
     * @Assert\Length(max=100, maxMessage="Le nom est trop long")
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Game", mappedBy="platforms")
     * @ORM\JoinColumn(nullable=true)
     */
    private $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGames(): ?Collection
    {
        return $this->games;
    }

    public function setGames(Game $games): self
    {
        $this->games = $games;

        return $this;
    }


}
