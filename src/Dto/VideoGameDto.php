<?php

namespace App\Dto;
use App\Entity\Platform;
use Symfony\Component\Validator\Constraints as Assert;
class VideoGameDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 100)]
        public readonly string $title,

        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 100)]
        public readonly string $genre,

        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 100)]
        public readonly string $developer,

        #[Assert\NotBlank]
        public readonly \DateTime $releaseDate,

        #[Assert\NotBlank]
        public array $platformNames
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function getDeveloper(): string
    {
        return $this->developer;
    }

    public function getReleaseDate(): \DateTime
    {
        return $this->releaseDate;
    }
    public function getPlatformNames(): array
    {
        return $this->platformNames;
    }
}
