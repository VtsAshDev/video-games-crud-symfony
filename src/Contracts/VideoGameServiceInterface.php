<?php

namespace App\Contracts;

use App\Dto\CreateVideoGameDto;
use App\Dto\VideoGameWithPlatformDto;
use App\Entity\VideoGame;

interface VideoGameServiceInterface
{
    public function create(CreateVideoGameDto $videoGameDto): void;

    public function findById(int $id): string;

    public function findByTitle(string $title): string;

    public function updateVideoGame(VideoGame $videoGame, VideoGameWithPlatformDto $videoGameDto): void;

    public function deleteVideoGame(VideoGame $videoGame): void;

    public function getVideoGamesByPlatform(int $platform): string;
}
