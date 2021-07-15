<?php

namespace App\Entity;

use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConversationRepository::class)
 */
class Conversation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $body;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="conversations")
     */
    private $user_1;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $user_2;

    /**
     * @ORM\OneToMany(targetEntity=MessageUser::class, mappedBy="conversation", cascade={"remove"})
     */
    private $messageUsers;

    public function __construct()
    {
        $this->createdAt=new \Datetime();
        $this->messageUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

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

    public function getUser1(): ?User
    {
        return $this->user_1;
    }

    public function setUser1(?User $user_1): self
    {
        $this->user_1 = $user_1;

        return $this;
    }

    public function getUser2(): ?User
    {
        return $this->user_2;
    }

    public function setUser2(?User $user_2): self
    {
        $this->user_2 = $user_2;

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
            $messageUser->setConversation($this);
        }

        return $this;
    }

    public function removeMessageUser(MessageUser $messageUser): self
    {
        if ($this->messageUsers->removeElement($messageUser)) {
            // set the owning side to null (unless already changed)
            if ($messageUser->getConversation() === $this) {
                $messageUser->setConversation(null);
            }
        }

        return $this;
    }
}
