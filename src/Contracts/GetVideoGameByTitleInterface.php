<?php

namespace App\Contracts;

interface GetVideoGameByTitleInterface
{
    public function __invoke(string $title): string ;
}
