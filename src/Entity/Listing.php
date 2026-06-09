<?php

namespace App\Entity;

use App\Enum\ListingStatus;
use App\Repository\ListingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListingRepository::class)]
class Listing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column]
    private int $price;

    #[ORM\Column(type: 'json')]
    private array $images = [];

    #[ORM\Column(enumType: ListingStatus::class)]
    private ListingStatus $status = ListingStatus::Active;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $location = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'listings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'listings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $seller = null;

    #[ORM\OneToMany(targetEntity: Conversation::class, mappedBy: 'listing')]
    private Collection $conversations;

    #[ORM\OneToMany(targetEntity: FakeOrder::class, mappedBy: 'listing')]
    private Collection $orders;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->conversations = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }

    public function getDescription(): string { return $this->description; }
    public function setDescription(string $description): static { $this->description = $description; return $this; }

    public function getPrice(): int { return $this->price; }
    public function setPrice(int $price): static { $this->price = $price; return $this; }

    public function getPriceFormatted(): string
    {
        return number_format($this->price / 100, 0, '.', ' ') . ' ₴';
    }

    public function getImages(): array { return $this->images; }
    public function setImages(array $images): static { $this->images = $images; return $this; }

    public function getFirstImage(): ?string
    {
        return $this->images[0] ?? null;
    }

    public function getStatus(): ListingStatus { return $this->status; }
    public function setStatus(ListingStatus $status): static { $this->status = $status; return $this; }

    public function getLocation(): ?string { return $this->location; }
    public function setLocation(?string $location): static { $this->location = $location; return $this; }

    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static { $this->createdAt = $createdAt; return $this; }

    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static { $this->updatedAt = $updatedAt; return $this; }

    public function getCategory(): ?Category { return $this->category; }
    public function setCategory(?Category $category): static { $this->category = $category; return $this; }

    public function getSeller(): ?User { return $this->seller; }
    public function setSeller(?User $seller): static { $this->seller = $seller; return $this; }

    public function getConversations(): Collection { return $this->conversations; }
    public function getOrders(): Collection { return $this->orders; }
}
