<?php

namespace App\Contracts;

use App\Dto\CreateVideoGameDto;

interface GetVideoGameByIdInterface
{
    public function __invoke(int $id): string;
}
