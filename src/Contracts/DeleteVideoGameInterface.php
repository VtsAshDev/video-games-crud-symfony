<?php

namespace App\Contracts;

use App\Entity\VideoGame;

interface DeleteVideoGameInterface
{
    public function __invoke(VideoGame $videoGame): void;
}
