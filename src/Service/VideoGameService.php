<?php

namespace App\Service;

use App\Contracts\VideoGameServiceInterface;
use App\Dto\CreateVideoGameDto;
use App\Dto\VideoGameWithPlatformDto;
use App\Entity\VideoGame;
use App\Mapper\VideoGameMapper;
use App\Repository\PlatformRepository;
use App\Repository\VideoGameRepository;
use Exception;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class VideoGameService implements VideoGameServiceInterface
{
    public function __construct(
        private VideoGameRepository $videoGameRepository,
        private PlatformRepository $platformRepository,
        private SerializerInterface $serializer,
        private VideoGameMapper $videoGameMapper
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

    public function findById(int $id): string
    {
        $videoGame = $this->videoGameRepository->find($id);

        if (!$videoGame) {
            throw new NotFoundHttpException("Video game nao encontrado");
        }

        $dto = $this->videoGameMapper->toDto($videoGame);

        return $this->serializer->serialize($dto, 'json');
    }

    public function findByTitle(string $title): string
    {
        $videoGame = $this->videoGameRepository->findVideoGameByName($title);
        if (!$videoGame) {
            throw new NotFoundHttpException("Video game nao encontrado");
        }

        $dto = $this->videoGameMapper->toDto($videoGame);

        return $this->serializer->serialize($dto, 'json');
    }

    public function updateVideoGame(VideoGame $videoGame,CreateVideoGameDto $videoGameDto): void
    {
        try {
            $videoGame->setTitle($videoGameDto->getTitle());
            $videoGame->setGenre($videoGameDto->getGenre());
            $videoGame->setDeveloper($videoGameDto->getDeveloper());
            $videoGame->setReleaseDate($videoGameDto->getReleaseDate());
            $videoGame->clearPlatforms();

            foreach ($videoGameDto->getPlatform() as $platform) {
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

    public function deleteVideoGame(VideoGame $videoGame): void
    {
        try {
            $this->videoGameRepository->beginTransaction();
            $this->videoGameRepository->deleteVideoGame($videoGame);
            $this->videoGameRepository->commit();
        } catch (Exception $e) {
            throw new BadRequestHttpException("Video game nao DELETADO");
        }
    }

    public function getVideoGamesByPlatform(int $platform): string
    {
        $games = $this->videoGameRepository->findByPlatform($platform);
        $jsonGames = [];

        foreach($games as $game)
        {
            $gameDTO = $this->videoGameMapper->toDto($game);
            $jsonGames[] = $gameDTO;
        }

        $json = $this->serializer->serialize($jsonGames, 'json');

        if (empty($games)) {
            throw new BadRequestHttpException("Nenhum video game encontrado");
        }

        return $json;
    }

}
