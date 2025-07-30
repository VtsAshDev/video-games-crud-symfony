<?php

namespace App\Service;

use App\Contracts\VideoGameServiceInterface;
use App\Dto\VideoGameDto;
use App\Entity\VideoGame;
use App\Repository\PlatformRepository;
use App\Repository\VideoGameRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\RuntimeException;

class VideoGameService implements VideoGameServiceInterface
{
    public function __construct(
        private VideoGameRepository $videoGameRepository,
        private readonly PlatformRepository $platformRepository
    ) {
    }

    /**
     * @throws \Exception
     */
    public function create(VideoGameDto $videoGameDto): void
    {
        $videoGame = new VideoGame();
        $videoGame->setTitle($videoGameDto->getTitle());
        $videoGame->setGenre($videoGameDto->getGenre());
        $videoGame->setDeveloper($videoGameDto->getDeveloper());
        $videoGame->setReleaseDate($videoGameDto->getReleaseDate());

        $platforms = $this->platformRepository->createQueryBuilder('p')
        ->where('p.name IN (:names)')
        ->setParameter('names', $videoGameDto->getPlatformNames())
        ->getQuery()
        ->getResult();

        foreach ($platforms as $platform) {
            $videoGame->addPlatform($platform);
        }

        try {
            $this->videoGameRepository->beginTransaction();
            $this->videoGameRepository->save($videoGame);
            $this->videoGameRepository->commit();
        } catch (\Exception $e) {
            $this->videoGameRepository->rollback();
            throw new RuntimeException("Nao foi possivel cadastrar o video game");
        }
    }

    public function findById(int $id): VideoGame
    {
        $videoGame = $this->videoGameRepository->find($id);

        if (null === $videoGame) {
            throw new \RuntimeException("Video game nao encontrado");
        }

        return $videoGame;
    }

    public function findByTitle(string $title): VideoGame
    {
        $videoGame = $this->videoGameRepository->findVideoGameByName($title);

        if (null === $videoGame) {
            throw new \RuntimeException("Video game nao encontrado");
        }

        return $videoGame;
    }

    public function updateVideoGame(VideoGame $videoGame,VideoGameDto $videoGameDto): void
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

    public function getVideoGamesByPlatform(string $platformName): array
    {
        $games = $this->videoGameRepository->findByPlatform($platformName);
        if (empty($games))
        {
            throw new \RuntimeException("Nenhum video game encontrado para plataforma:". $platformName);
        }
        return $games;
    }

}
