<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EditorRepository")
 */
class Editor
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $society;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nationality;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="editor")
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

    public function getSociety(): ?string
    {
        return $this->society;
    }

    public function setSociety(string $society): self
    {
        $this->society = $society;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->game->contains($game)) {
            $this->games[] = $game;
            $game->setEditor($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->game->contains($game)) {
            $this->game->removeElement($game);
            // set the owning side to null (unless already changed)
            if ($game->getEditor() === $this) {
                $game->setEditor(null);
            }
        }

        return $this;
    }
}
