<?php

namespace App\Service;

use App\Contracts\UpdateVideoGameInterface;
use App\Contracts\VideoGameServiceInterface;
use App\Dto\CreateVideoGameDto;
use App\Entity\VideoGame;
use App\Repository\PlatformRepository;
use App\Repository\VideoGameRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;
readonly class UpdateVideoGameService implements UpdateVideoGameInterface
{
    public function __construct(
        private PlatformRepository $platformRepository,
        private VideoGameRepository $videoGameRepository
    ) {
    }

    public function __invoke(VideoGame $videoGame, CreateVideoGameDto $videoGameDto): void
    {
        try {
            $videoGame->setTitle($videoGameDto->getTitle());
            $videoGame->setGenre($videoGameDto->getGenre());
            $videoGame->setDeveloper($videoGameDto->getDeveloper());
            $videoGame->setReleaseDate($videoGameDto->getReleaseDate());
            $videoGame->clearPlatforms();

            foreach ($videoGameDto->getPlatforms() as $platform) {
                $findedPlatform = $this->platformRepository->find($platform);
                if (!$findedPlatform) {
                    throw new NotFoundHttpException("Plataforma nao encontrado");
                }

                $videoGame->addPlatform($findedPlatform);
            }

            $this->videoGameRepository->beginTransaction();
            $this->videoGameRepository->updateVideoGame($videoGame);
            $this->videoGameRepository->commit();
        } catch (Exception $e) {
            $this->videoGameRepository->rollback();
            throw new BadRequestHttpException("Video game nao ATUALIZADO");
        }
    }
}
