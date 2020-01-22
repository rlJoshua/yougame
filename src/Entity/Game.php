<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Platform")
     * @ORM\JoinColumn(nullable=false)
     */
    private $platforms;

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
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $editor;

    public function __construct()
    {
        $this->platforms = new ArrayCollection();
    }

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

    public function getPlatforms(): ?Collection
    {
        return $this->platforms;
    }

    public function setPlatforms(Collection $platforms): self
    {
        $this->platforms = $platforms;
        return $this;
    }

    public function addPlatform(Platform $platform): self
    {
        if (!$this->platforms->contains($platform)) {
            $this->platforms += $platform;
            //$game->setEditor($this);
        }
        return $this;
    }

    public function removePlatform(Platform $platform): self
    {
        if ($this->platforms->contains($platform)) {
            $this->platforms->removeElement($platform);
            // set the owning side to null (unless already changed)
            /*if ($game->getEditor() === $this) {
                $game->setEditor(null);
            }*/
        }

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
