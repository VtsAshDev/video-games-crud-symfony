<?php

namespace App\Service;

use App\Repository\PlatformRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class PlatformService
{
    public function __construct(
        private readonly PlatformRepository $platformRepository
    ) {
    }

    public function getDefaultPlatforms(): array
    {
       return $this->platformRepository->findDefaults();
    }

}
