<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $slot1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $slot2;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $slot3;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $slot4;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $slot5;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="teams")
     */
    private $Owner;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="team")
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity=VideoGame::class, mappedBy="team")
     */
    private $game;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->game = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlot1(): ?int
    {
        return $this->slot1;
    }

    public function setSlot1(?int $slot1): self
    {
        $this->slot1 = $slot1;

        return $this;
    }

    public function getSlot2(): ?int
    {
        return $this->slot2;
    }

    public function setSlot2(?int $slot2): self
    {
        $this->slot2 = $slot2;

        return $this;
    }

    public function getSlot3(): ?int
    {
        return $this->slot3;
    }

    public function setSlot3(?int $slot3): self
    {
        $this->slot3 = $slot3;

        return $this;
    }

    public function getSlot4(): ?int
    {
        return $this->slot4;
    }

    public function setSlot4(?int $slot4): self
    {
        $this->slot4 = $slot4;

        return $this;
    }

    public function getSlot5(): ?int
    {
        return $this->slot5;
    }

    public function setSlot5(?int $slot5): self
    {
        $this->slot5 = $slot5;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->Owner;
    }

    public function setOwner(?User $Owner): self
    {
        $this->Owner = $Owner;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setTeam($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getTeam() === $this) {
                $message->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|VideoGame[]
     */
    public function getGame(): Collection
    {
        return $this->game;
    }

    public function addGame(VideoGame $game): self
    {
        if (!$this->game->contains($game)) {
            $this->game[] = $game;
            $game->setTeam($this);
        }

        return $this;
    }

    public function removeGame(VideoGame $game): self
    {
        if ($this->game->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getTeam() === $this) {
                $game->setTeam(null);
            }
        }

        return $this;
    }
}
