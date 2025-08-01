<?php

namespace App\Contracts;

use App\Dto\CreateVideoGameDto;
use App\Entity\VideoGame;

interface UpdateVideoGameInterface
{
    public function __invoke(VideoGame $videoGame,CreateVideoGameDto $videoGameDto): void;
}
