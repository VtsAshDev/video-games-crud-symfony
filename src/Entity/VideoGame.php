<?php

namespace App\Entity;

use App\Repository\VideoGameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoGameRepository::class)]
class VideoGame
{
    #[ORM\ManyToMany(targetEntity: Platform::class, inversedBy: 'video_games')]
    #[ORM\JoinTable(name: 'video_game_platform')]
    private Collection $platforms;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 100)]
    private ?string $genre = null;

    #[ORM\Column(length: 100)]
    private ?string $developer = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $releaseDate = null;

    public function __construct() {
        $this->platforms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getDeveloper(): ?string
    {
        return $this->developer;
    }

    public function setDeveloper(string $developer): static
    {
        $this->developer = $developer;

        return $this;
    }

    public function getReleaseDate(): ?\DateTime
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTime $releaseDate): static
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getPlatforms() : Collection
    {
        return $this->platforms;
    }

    public function addPlatform(Platform $platform): self
    {
        if (!$this->platforms->contains($platform)) {
            $this->platforms->add($platform);
        }
        return $this;
    }

    public function removePlatform(Platform $platform): self
    {
        $this->platforms->removeElement($platform);
        return $this;
    }
}
