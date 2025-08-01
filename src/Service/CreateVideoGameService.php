<?php

namespace App\Service;

use App\Contracts\CreateVideoGameInterface;
use App\Dto\CreateVideoGameDto;
use App\Entity\VideoGame;
use App\Repository\PlatformRepository;
use App\Repository\VideoGameRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;
readonly class CreateVideoGameService implements CreateVideoGameInterface
{
    public function __construct(
        private PlatformRepository $platformRepository,
        private VideoGameRepository $videoGameRepository
    ) {
    }

    public function create(CreateVideoGameDto $videoGameDto): void
    {
        try {
            $videoGame = new VideoGame();
            $videoGame->setTitle($videoGameDto->getTitle());
            $videoGame->setGenre($videoGameDto->getGenre());
            $videoGame->setDeveloper($videoGameDto->getDeveloper());
            $videoGame->setReleaseDate($videoGameDto->getReleaseDate());

            $platforms = $this->platformRepository->findPlatforms($videoGameDto->getPlatform());
            if (empty($platforms)) {
                throw new NotFoundHttpException("Plataformas nao podem estar em branco");
            }

            foreach ($platforms as $platform) {
                $videoGame->addPlatform($platform);
            }

            $this->videoGameRepository->beginTransaction();
            $this->videoGameRepository->save($videoGame);
            $this->videoGameRepository->commit();
        } catch (Exception $e) {
            $this->videoGameRepository->rollback();
            throw new BadRequestHttpException("Não foi possível cadastrar o video game");
        }
    }
}
