<?php

namespace App\Mapper;

use App\Dto\VideoGameWithPlatformDto;
use App\Entity\VideoGame;

class VideoGameMapper
{
    public  function toDto(VideoGame $videoGame): VideoGameWithPlatformDto
    {
        $platformNames = array_map(
            fn($platform) => $platform->getName(),
            $videoGame->getPlatforms()->toArray()
        );

        return new VideoGameWithPlatformDto(
            $videoGame->getId(),
            $videoGame->getTitle(),
            $videoGame->getGenre(),
            $videoGame->getDeveloper(),
            $videoGame->getReleaseDate(),
            $platformNames
        );
    }
}
