<?php

namespace App\Contracts;

interface GetVideoGamsByPlatformInterface
{
    public function __invoke(int $platform): string;
}
