<?php

namespace App\Controller;

use App\Entity\VideoGame;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class VideoGameController
{
    #[Route('/api/videogame', name: 'create_video_game')]
    public function createVideoGame(EntityManagerInterface $entityManager): Response
    {
        $videoGame = new VideoGame();
        $videoGame->setTitle("bom de guerra");
        $videoGame->setGenre("Acao");
        $videoGame->setDeveloper("santa monica");
        $videoGame->setReleaseDate(new \DateTime());

        $entityManager->persist($videoGame);
        $entityManager->flush();
        return new Response("Novo video game salvo with id: " . $videoGame->getId());
    }

    #[Route('/api/videogame/{id}')]
    public function show(?VideoGame $videoGame): Response
    {
//        $videoGame = $entityManager->getRepository(VideoGame::class)->find($id);

        if (null === $videoGame){
            throw new NotFoundHttpException("Video game nao encontrado");
        }
        return new Response('Video game encontrado :'. $videoGame->getTitle());
    }
}
