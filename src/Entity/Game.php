<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $frequency;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $nextDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="boolean")
     */
    private $open;

    /**
     * @ORM\Column(type="smallint")
     */
    private $maxPlayer;

    /**
     * @ORM\Column(type="datetime")
    */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class)
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=GameMessage::class, mappedBy="game", orphanRemoval=true)
     */
    private $gameMessages;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="creator")
     */
    private $creator;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="guests")
     */
    private $guests;

    public function __construct()
    {
        $this->createdAt=new \Datetime();
        $this->tags = new ArrayCollection();
        $this->gameMessages = new ArrayCollection();
        $this->guests = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getFrequency(): ?string
    {
        return $this->frequency;
    }

    public function setFrequency(string $frequency): self
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function getNextDate(): ?\DateTimeInterface
    {
        return $this->nextDate;
    }

    public function setNextDate(?\DateTimeInterface $nextDate): self
    {
        $this->nextDate = $nextDate;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getOpen(): ?bool
    {
        return $this->open;
    }

    public function setOpen(bool $open): self
    {
        $this->open = $open;

        return $this;
    }

    public function getMaxPlayer(): ?int
    {
        return $this->maxPlayer;
    }

    public function setMaxPlayer(int $maxPlayer): self
    {
        $this->maxPlayer = $maxPlayer;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

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
            $gameMessage->setGame($this);
        }
        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getGuests(): Collection
    {
        return $this->guests;
    }

    public function addGuest(User $guest): self
    {
        if (!$this->guests->contains($guest)) {
            $this->guests[] = $guest;
        }

        return $this;
    }

    public function removeGameMessage(GameMessage $gameMessage): self
    {
        if ($this->gameMessages->removeElement($gameMessage)) {
            // set the owning side to null (unless already changed)
            if ($gameMessage->getGame() === $this) {
                $gameMessage->setGame(null);
            }
        }

        return $this;
    }

    public function removeGuest(User $guest): self
    {
        $this->guests->removeElement($guest);

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
