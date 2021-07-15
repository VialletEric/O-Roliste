<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface //, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user_read"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     * @Groups({"user_read"})
     */
    private $username;

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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user_read"})
     */
    private $avatar;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=GameMessage::class, mappedBy="user")
     */
    private $gameMessages;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="creator")
     */
    private $creator;

    /**
     * @ORM\ManyToMany(targetEntity=Game::class, mappedBy="guests")
     */
    private $guests;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="myFriends")
     */
    private $friendsWithMe;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="friendsWithMe")
     */
    private $myFriends;

    /**
     * @ORM\OneToMany(targetEntity=Conversation::class, mappedBy="user_1")
     */
    private $conversations;

    /**
     * @ORM\OneToMany(targetEntity=MessageUser::class, mappedBy="user")
     */
    private $messageUsers;

    public function __construct()
    {
        $this->setRoles(["ROLE_USER"]);
        $this->createdAt = new \Datetime();
        $this->gameMessages = new ArrayCollection();
        $this->creator = new ArrayCollection();
        $this->guests = new ArrayCollection();
        $this->friendsWithMe = new ArrayCollection();
        $this->myFriends = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->messageUsers = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->username;
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|GameMessage[]
     */
    public function getGameMessages(): Collection
    {
        return $this->gameMessages;
    }

    public function addGameMessage(GameMessage $gameMessage): self
    {
        if (!$this->gameMessages->contains($gameMessage)) {
            $this->gameMessages[] = $gameMessage;
            $gameMessage->setUser($this);
        }
        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getCreator(): Collection
    {
        return $this->creator;
    }

    public function addCreator(Game $creator): self
    {
        if (!$this->creator->contains($creator)) {
            $this->creator[] = $creator;
            $creator->setCreator($this);
        }

        return $this;
    }

    public function removeGameMessage(GameMessage $gameMessage): self
    {
        if ($this->gameMessages->removeElement($gameMessage)) {
            // set the owning side to null (unless already changed)
            if ($gameMessage->getUser() === $this) {
                $gameMessage->setUser(null);
            }
        }
        return $this;
    }

    public function removeCreator(Game $creator): self
    {
        if ($this->creator->removeElement($creator)) {
            // set the owning side to null (unless already changed)
            if ($creator->getCreator() === $this) {
                $creator->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGuests(): Collection
    {
        return $this->guests;
    }

    public function addGuest(Game $guest): self
    {
        if (!$this->guests->contains($guest)) {
            $this->guests[] = $guest;
            $guest->addGuest($this);
        }

        return $this;
    }

    public function removeGuest(Game $guest): self
    {
        if ($this->guests->removeElement($guest)) {
            $guest->removeGuest($this);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFriendsWithMe(): Collection
    {
        return $this->friendsWithMe;
    }

    public function addFriendsWithMe(self $friendsWithMe): self
    {
        if (!$this->friendsWithMe->contains($friendsWithMe)) {
            $this->friendsWithMe[] = $friendsWithMe;
        }

        return $this;
    }

    /**        
     * @return Collection|Conversation[]
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
            $conversation->setUser1($this);
        }

        return $this;
    }

    public function removeFriendsWithMe(self $friendsWithMe): self
    {
        $this->friendsWithMe->removeElement($friendsWithMe);

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getUser1() === $this) {
                $conversation->setUser1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getMyFriends(): Collection
    {
        return $this->myFriends;
    }

    public function addMyFriend(self $myFriend): self
    {
        if (!$this->myFriends->contains($myFriend)) {
            $this->myFriends[] = $myFriend;
            $myFriend->addFriendsWithMe($this);
        }

        return $this;
    }
    /**
     * @return Collection|MessageUser[]
     */
    public function getMessageUsers(): Collection
    {
        return $this->messageUsers;
    }

    public function addMessageUser(MessageUser $messageUser): self
    {
        if (!$this->messageUsers->contains($messageUser)) {
            $this->messageUsers[] = $messageUser;
            $messageUser->setUser($this);
        }

        return $this;
    }

    public function removeMyFriend(self $myFriend): self
    {
        if ($this->myFriends->removeElement($myFriend)) {
            $myFriend->removeFriendsWithMe($this);
        }

        return $this;
    }

    public function removeMessageUser(MessageUser $messageUser): self
    {
        if ($this->messageUsers->removeElement($messageUser)) {
            // set the owning side to null (unless already changed)
            if ($messageUser->getUser() === $this) {
                $messageUser->setUser(null);
            }
        }

        return $this;
    }
}
