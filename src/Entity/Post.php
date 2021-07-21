<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
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
    private $Title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Content;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     */
    private $author;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $flexPoint;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="flexList")
     */
    private $flexers;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    public function __construct()
    {
        $this->flex = new ArrayCollection();
        $this->monsterflex = new ArrayCollection();
        $this->flexers = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(?string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->Content;
    }

    public function setContent(?string $Content): self
    {
        $this->Content = $Content;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getFlexPoint(): ?int
    {
        return $this->flexPoint;
    }

    public function setFlexPoint(?int $flexPoint): self
    {
        $this->flexPoint = $flexPoint;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFlexers(): Collection
    {
        return $this->flexers;
    }

    public function addFlexer(User $flexer): self
    {
        if (!$this->flexers->contains($flexer)) {
            $this->flexers[] = $flexer;
            $flexer->addFlexList($this);
        }

        return $this;
    }

    public function removeFlexer(User $flexer): self
    {
        if ($this->flexers->removeElement($flexer)) {
            $flexer->removeFlexList($this);
        }

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
