<?php

namespace App\Contracts;

use App\Dto\VideoGameDto;
use App\Entity\VideoGame;

interface VideoGameServiceInterface
{
    public function create(VideoGameDto $videoGameDto): void;

    public function findById(int $id): ?VideoGame;

    public function findByTitle(string $title): VideoGame;

    public function updateVideoGame(VideoGame $videoGame, VideoGameDto $videoGameDto): void;

    public function deleteVideoGame(VideoGame $videoGame): void;
}
