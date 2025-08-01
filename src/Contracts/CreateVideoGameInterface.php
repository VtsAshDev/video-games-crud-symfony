<?php

namespace App\Contracts;

use App\Dto\CreateVideoGameDto;

interface CreateVideoGameInterface
{
    public function __invoke(CreateVideoGameDto $videoGameDto): void;
}
