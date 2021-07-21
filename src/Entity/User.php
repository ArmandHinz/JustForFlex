<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="author")
     */
    private $posts;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $totalFlex;

    /**
     * @ORM\ManyToMany(targetEntity=VideoGame::class, mappedBy="user")
     */
    private $videoGames;

    /**
     * @ORM\OneToMany(targetEntity=Team::class, mappedBy="Owner")
     */
    private $teams;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="author")
     */
    private $messages;

    /**
     * @ORM\ManyToMany(targetEntity=Post::class, inversedBy="flexers")
     */
    private $flexList;

    /**
     * @ORM\OneToMany(targetEntity=Team::class, mappedBy="slot1")
     */
    private $teamslot1;

    /**
     * @ORM\OneToMany(targetEntity=Team::class, mappedBy="slot2")
     */
    private $teamslot2;

    /**
     * @ORM\OneToMany(targetEntity=Team::class, mappedBy="slot3")
     */
    private $teamslot3;

    /**
     * @ORM\OneToMany(targetEntity=Team::class, mappedBy="slot4")
     */
    private $teamslot4;

    /**
     * @ORM\OneToMany(targetEntity=Team::class, mappedBy="slot5")
     */
    private $teamslot5;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->flex = new ArrayCollection();
        $this->monsterflex = new ArrayCollection();
        $this->videoGames = new ArrayCollection();
        $this->teams = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->flexList = new ArrayCollection();
        $this->teamslot1 = new ArrayCollection();
        $this->teamslot2 = new ArrayCollection();
        $this->teamslot3 = new ArrayCollection();
        $this->teamslot4 = new ArrayCollection();
        $this->teamslot5 = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
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

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getTotalFlex(): ?int
    {
        return $this->totalFlex;
    }

    public function setTotalFlex(?int $totalFlex): self
    {
        $this->totalFlex = $totalFlex;

        return $this;
    }

    /**
     * @return Collection|VideoGame[]
     */
    public function getVideoGames(): Collection
    {
        return $this->videoGames;
    }

    public function addVideoGame(VideoGame $videoGame): self
    {
        if (!$this->videoGames->contains($videoGame)) {
            $this->videoGames[] = $videoGame;
            $videoGame->addUser($this);
        }

        return $this;
    }

    public function removeVideoGame(VideoGame $videoGame): self
    {
        if ($this->videoGames->removeElement($videoGame)) {
            $videoGame->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->setOwner($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->removeElement($team)) {
            // set the owning side to null (unless already changed)
            if ($team->getOwner() === $this) {
                $team->setOwner(null);
            }
        }

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
            $message->setAuthor($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getAuthor() === $this) {
                $message->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getFlexList(): Collection
    {
        return $this->flexList;
    }

    public function addFlexList(Post $flexList): self
    {
        if (!$this->flexList->contains($flexList)) {
            $this->flexList[] = $flexList;
        }

        return $this;
    }

    public function removeFlexList(Post $flexList): self
    {
        $this->flexList->removeElement($flexList);

        return $this;
    }

    public function isInFlexlist(Post $post)
    {
        if ($this->flexList->contains($post)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeamslot1(): Collection
    {
        return $this->teamslot1;
    }

    public function addTeamslot1(Team $teamslot1): self
    {
        if (!$this->teamslot1->contains($teamslot1)) {
            $this->teamslot1[] = $teamslot1;
            $teamslot1->setSlot1($this);
        }

        return $this;
    }

    public function removeTeamslot1(Team $teamslot1): self
    {
        if ($this->teamslot1->removeElement($teamslot1)) {
            // set the owning side to null (unless already changed)
            if ($teamslot1->getSlot1() === $this) {
                $teamslot1->setSlot1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeamslot2(): Collection
    {
        return $this->teamslot2;
    }

    public function addTeamslot2(Team $teamslot2): self
    {
        if (!$this->teamslot2->contains($teamslot2)) {
            $this->teamslot2[] = $teamslot2;
            $teamslot2->setSlot2($this);
        }

        return $this;
    }

    public function removeTeamslot2(Team $teamslot2): self
    {
        if ($this->teamslot2->removeElement($teamslot2)) {
            // set the owning side to null (unless already changed)
            if ($teamslot2->getSlot2() === $this) {
                $teamslot2->setSlot2(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeamslot3(): Collection
    {
        return $this->teamslot3;
    }

    public function addTeamslot3(Team $teamslot3): self
    {
        if (!$this->teamslot3->contains($teamslot3)) {
            $this->teamslot3[] = $teamslot3;
            $teamslot3->setSlot3($this);
        }

        return $this;
    }

    public function removeTeamslot3(Team $teamslot3): self
    {
        if ($this->teamslot3->removeElement($teamslot3)) {
            // set the owning side to null (unless already changed)
            if ($teamslot3->getSlot3() === $this) {
                $teamslot3->setSlot3(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeamslot4(): Collection
    {
        return $this->teamslot4;
    }

    public function addTeamslot4(Team $teamslot4): self
    {
        if (!$this->teamslot4->contains($teamslot4)) {
            $this->teamslot4[] = $teamslot4;
            $teamslot4->setSlot4($this);
        }

        return $this;
    }

    public function removeTeamslot4(Team $teamslot4): self
    {
        if ($this->teamslot4->removeElement($teamslot4)) {
            // set the owning side to null (unless already changed)
            if ($teamslot4->getSlot4() === $this) {
                $teamslot4->setSlot4(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeamslot5(): Collection
    {
        return $this->teamslot5;
    }

    public function addTeamslot5(Team $teamslot5): self
    {
        if (!$this->teamslot5->contains($teamslot5)) {
            $this->teamslot5[] = $teamslot5;
            $teamslot5->setSlot5($this);
        }

        return $this;
    }

    public function removeTeamslot5(Team $teamslot5): self
    {
        if ($this->teamslot5->removeElement($teamslot5)) {
            // set the owning side to null (unless already changed)
            if ($teamslot5->getSlot5() === $this) {
                $teamslot5->setSlot5(null);
            }
        }

        return $this;
    }
}
