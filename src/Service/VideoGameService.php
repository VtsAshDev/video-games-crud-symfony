<?php

namespace App\Service;

use App\Contracts\VideoGameServiceInterface;
use App\Dto\CreateVideoGameDto;
use App\Dto\VideoGameWithPlatformDto;
use App\Entity\VideoGame;
use App\Mapper\VideoGameMapper;
use App\Repository\PlatformRepository;
use App\Repository\VideoGameRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class VideoGameService implements VideoGameServiceInterface
{
    public function __construct(
        private VideoGameRepository $videoGameRepository,
        private readonly PlatformRepository $platformRepository,
        private SerializerInterface $serializer,
        private readonly VideoGameMapper $videoGameMapper
    ) {
    }

    /**
     * @throws \Exception
     */
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
        } catch (\Exception $e) {
            $this->videoGameRepository->rollback();
            throw new \RuntimeException("Não foi possível cadastrar o video game");
        }
    }

    /**
     * @throws ExceptionInterface
     */
    public function findById(int $id): string
    {
        $videoGame = $this->videoGameRepository->find($id);

        if (!$videoGame) {
            throw new NotFoundHttpException("Video game nao encontrado");
        }

        $dto = $this->videoGameMapper->toDto($videoGame);

        return $this->serializer->serialize($dto, 'json');
    }

    /**
     * @throws ExceptionInterface
     */
    public function findByTitle(string $title): string
    {
        $videoGame = $this->videoGameRepository->findVideoGameByName($title);
        if (!$videoGame) {
            throw new NotFoundHttpException("Video game nao encontrado");
        }

        $dto = $this->videoGameMapper->toDto($videoGame);

        return $this->serializer->serialize($dto, 'json');
    }

    public function updateVideoGame(VideoGame $videoGame,VideoGameWithPlatformDto $videoGameDto): void
    {
        $hasChanges = false;

        if ($videoGame->getTitle() !== $videoGameDto->getTitle()) {
            $videoGame->setTitle($videoGameDto->getTitle());
            $hasChanges = true;
        }
        if ($videoGame->getGenre() !== $videoGameDto->getGenre()) {
            $videoGame->setGenre($videoGameDto->getGenre());
            $hasChanges = true;
        }
        if ($videoGame->getDeveloper() !== $videoGameDto->getDeveloper()) {
            $videoGame->setDeveloper($videoGameDto->getDeveloper());
            $hasChanges = true;
        }

        if (!$hasChanges) {
            throw new \RuntimeException("Não há dados a serem alterados no VideoGame: ". $videoGame->getTitle());
        }

        try {
            $this->videoGameRepository->beginTransaction();
            $this->videoGameRepository->updateVideoGame($videoGame);
            $this->videoGameRepository->commit();
        } catch (\Exception $e) {
            $this->videoGameRepository->rollback();
            throw new \RuntimeException("Video game nao ATUALIZADO");
        }

    }

    public function deleteVideoGame(VideoGame $videoGame): void
    {
        try {
            $this->videoGameRepository->beginTransaction();
            $this->videoGameRepository->deleteVideoGame($videoGame);
            $this->videoGameRepository->commit();
        } catch (\Exception $e) {
            throw new \RuntimeException("Video game nao DELETADO");
        }
    }

    /**
     * @throws ExceptionInterface
     */
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
            throw new \RuntimeException("Nenhum video game encontrado");
        }

        return $json;
    }

}
