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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="teams")
     */
    private $Owner;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="team")
     */
    private $messages;

    /**
     * @ORM\ManyToOne(targetEntity=VideoGame::class, inversedBy="teams")
     */
    private $videoGame;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberMate;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="teamslot1")
     */
    private $slot1;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="teamslot2")
     */
    private $slot2;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="teamslot3")
     */
    private $slot3;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="teamslot4")
     */
    private $slot4;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="teamslot5")
     */
    private $slot5;

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

    public function getVideoGame(): ?VideoGame
    {
        return $this->videoGame;
    }

    public function setVideoGame(?VideoGame $videoGame): self
    {
        $this->videoGame = $videoGame;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNumberMate(): ?int
    {
        return $this->numberMate;
    }

    public function setNumberMate(?int $numberMate): self
    {
        $this->numberMate = $numberMate;

        return $this;
    }

    public function getSlot1(): ?User
    {
        return $this->slot1;
    }

    public function setSlot1(?User $slot1): self
    {
        $this->slot1 = $slot1;

        return $this;
    }

    public function getSlot2(): ?User
    {
        return $this->slot2;
    }

    public function setSlot2(?User $slot2): self
    {
        $this->slot2 = $slot2;

        return $this;
    }

    public function getSlot3(): ?User
    {
        return $this->slot3;
    }

    public function setSlot3(?User $slot3): self
    {
        $this->slot3 = $slot3;

        return $this;
    }

    public function getSlot4(): ?User
    {
        return $this->slot4;
    }

    public function setSlot4(?User $slot4): self
    {
        $this->slot4 = $slot4;

        return $this;
    }

    public function getSlot5(): ?User
    {
        return $this->slot5;
    }

    public function setSlot5(?User $slot5): self
    {
        $this->slot5 = $slot5;

        return $this;
    }
}
