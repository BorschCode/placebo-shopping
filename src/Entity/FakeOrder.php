<?php

namespace App\Entity;

use App\Enum\OrderStatus;
use App\Repository\FakeOrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FakeOrderRepository::class)]
class FakeOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: OrderStatus::class)]
    private OrderStatus $status = OrderStatus::Pending;

    #[ORM\Column(type: 'text')]
    private string $deliveryAddress;

    #[ORM\Column]
    private int $estimatedMinutes;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(targetEntity: Listing::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Listing $listing = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $buyer = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->estimatedMinutes = random_int(20, 90);
    }

    public function getId(): ?int { return $this->id; }

    public function getStatus(): OrderStatus { return $this->status; }
    public function setStatus(OrderStatus $status): static { $this->status = $status; return $this; }

    public function getDeliveryAddress(): string { return $this->deliveryAddress; }
    public function setDeliveryAddress(string $deliveryAddress): static { $this->deliveryAddress = $deliveryAddress; return $this; }

    public function getEstimatedMinutes(): int { return $this->estimatedMinutes; }
    public function setEstimatedMinutes(int $estimatedMinutes): static { $this->estimatedMinutes = $estimatedMinutes; return $this; }

    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static { $this->createdAt = $createdAt; return $this; }

    public function getListing(): ?Listing { return $this->listing; }
    public function setListing(?Listing $listing): static { $this->listing = $listing; return $this; }

    public function getBuyer(): ?User { return $this->buyer; }
    public function setBuyer(?User $buyer): static { $this->buyer = $buyer; return $this; }
}
