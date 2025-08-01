<?php

namespace App\Dto;
use App\Entity\Platform;
use DateTime;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema()]
class CreateVideoGameDto
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
        public readonly DateTime $releaseDate,

        #[OA\Property(
            description: "Lista de IDs das plataformas",
            type: "array",
            items: new OA\Items(type: "integer")
        )]
        #[Assert\NotBlank(message: "Plataforma nao pode ser vazio")]
        public array  $platforms,
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

    public function getReleaseDate(): DateTime
    {
        return $this->releaseDate;
    }

    public function getPlatforms(): array
    {
        return $this->platforms;
    }

}
