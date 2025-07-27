<?php

namespace App\Controller;

use App\Entity\VideoGame;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class VideoGameController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/api/videogame', name: 'create_video_game')]
    public function createVideoGame(): Response
    {
        $videoGame = new VideoGame();
        $videoGame->setTitle("bom de guerra");
        $videoGame->setGenre("Acao");
        $videoGame->setDeveloper("santa monica");
        $videoGame->setReleaseDate(new \DateTime());

        $this->entityManager->persist($videoGame);
        $this->entityManager->flush();
        return new Response("Novo video game salvo with id: " . $videoGame->getId());
    }

    #[Route('/api/videogame/{id}', name: 'show_video_game')]
    public function show(?VideoGame $videoGame): Response
    {
//        $videoGame = $entityManager->getRepository(VideoGame::class)->find($id);

        if (null === $videoGame) {
            throw new NotFoundHttpException("Video game nao encontrado");
        }
        return new Response('Video game encontrado :' . $videoGame->getTitle());
    }

    #[Route('/api/videogame/title/{title}')]
    public function showByTitle(string $title): Response
    {
        $videogame = $this->entityManager->getRepository(VideoGame::class)->findVideoGameByName($title);

        if (null !== $videogame) {
            throw new NotFoundHttpException("Video game nao encontrado");
        }

        return new Response('Video game encontrado :' . $videogame['title']);
    }

    #[Route('/api/videogame/edit/{id}', name: 'edit_video_game')]
    public function update(?VideoGame $videoGame): Response
    {

        if (!$videoGame) {
            throw new NotFoundHttpException(
                "Video game nao encontrado"
            );
        }

        $videoGame->setTitle("bom de guerra");
        $this->entityManager->persist($videoGame);
        $this->entityManager->flush();

        return $this->show($videoGame);
    }

    #[Route('/api/videogame/delete/{id}', name: 'delete_video_game')]
    public function delete(?VideoGame $videoGame): Response
    {
        if (!$videoGame) {
            throw new NotFoundHttpException(
                "Video game nao encontrado para deletar"
            );
        }

        $this->entityManager->remove($videoGame);
        $this->entityManager->flush();
        return new Response("Usuario deleteado com sucesso");
    }


}
