<?php

namespace App\Entity;

use App\Enum\Theme;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private string $name;

    #[ORM\Column(length: 100, unique: true)]
    private string $slug;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $icon = null;

    #[ORM\Column(enumType: Theme::class)]
    private Theme $themeType;

    #[ORM\OneToMany(targetEntity: Listing::class, mappedBy: 'category')]
    private Collection $listings;

    public function __construct()
    {
        $this->listings = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }

    public function getSlug(): string { return $this->slug; }
    public function setSlug(string $slug): static { $this->slug = $slug; return $this; }

    public function getIcon(): ?string { return $this->icon; }
    public function setIcon(?string $icon): static { $this->icon = $icon; return $this; }

    public function getThemeType(): Theme { return $this->themeType; }
    public function setThemeType(Theme $themeType): static { $this->themeType = $themeType; return $this; }

    public function getListings(): Collection { return $this->listings; }
}
