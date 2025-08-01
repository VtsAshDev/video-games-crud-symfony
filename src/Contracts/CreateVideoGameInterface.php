<?php

namespace App\Contracts;

use App\Dto\CreateVideoGameDto;

interface CreateVideoGameInterface
{
    public function create(CreateVideoGameDto $videoGameDto): void;
}
