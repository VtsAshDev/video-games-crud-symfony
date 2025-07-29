<?php

namespace App\Contracts;

use App\Entity\VideoGame;

interface VideoGameServiceInterface
{
    public function create(): void;

    public function findById(int $id): ?VideoGame;

    public function findByTitle(string $title): VideoGame;

    public function updateVideoGame(VideoGame $videoGame): void;

    public function deleteVideoGame(VideoGame $videoGame): void;
}
