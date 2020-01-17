<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank(message="Le prénom ne peut pas être vide")
     * @Assert\Length(max="150", maxMessage="Le titre est trop long")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100)
     *  @Assert\NotBlank(message="La plateforme ne peut pas être vide")
     *  @Assert\Length(max="100", maxMessage="La plateforme est trop longue")
     */
    private $plateform;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La decription ne peut pas être vide")
     * @Assert\Length(max="255", maxMessage="La description est trop longue")
     */
    private $description;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $releaseDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Editor", inversedBy="games")
     * @ORM\JoinColumn(nullable=true)
     */
    private $editor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPlateform(): ?string
    {
        return $this->plateform;
    }

    public function setPlateform(string $plateform): self
    {
        $this->plateform = $plateform;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getEditor(): ?Editor
    {
        return $this->editor;
    }

    public function setEditor(?Editor $editor): self
    {
        $this->editor = $editor;

        return $this;
    }
}
