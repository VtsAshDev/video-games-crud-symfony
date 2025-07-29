<?php

namespace App\Service;

use App\Contracts\VideoGameServiceInterface;
use App\Entity\VideoGame;
use App\Repository\VideoGameRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\RuntimeException;

class VideoGameService implements VideoGameServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private VideoGameRepository $videoGameRepository
    ) {
    }


    /**
     * @throws \Exception
     */
    public function create(): void
    {
        $videoGame = new VideoGame();
        $videoGame->setTitle("bom de guerra");
        $videoGame->setGenre("Acao");
        $videoGame->setDeveloper("santa monica");
        $videoGame->setReleaseDate(new \DateTime());

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

    public function updateVideoGame(VideoGame $videoGame): void
    {
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

}
