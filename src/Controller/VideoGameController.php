<?php

namespace App\Controller;

use App\Contracts\VideoGameServiceInterface;
use App\Entity\VideoGame;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

class VideoGameController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private VideoGameServiceInterface $videoGameService
    ) {
    }

    #[Route('/api/videogame', name: 'create_video_game')]
    public function createVideoGame(): Response
    {
        try {
            $this->videoGameService->create();
        } catch (\Throwable $exception) {
            throw new NotFoundHttpException("Erro ao criar o video game");
        }

        return new Response("Video game Criado com sucesso");
    }

    #[Route('/api/videogame/{id}', name: 'show_video_game')]
    public function show(int $id): Response
    {
        try {
            $videoGame = $this->videoGameService->findById($id);

            return new Response('Video game encontrado: ' . $videoGame->getTitle());
        } catch (Throwable $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }
    }

    #[Route('/api/videogame/title/{title}')]
    public function showByTitle(string $title): Response
    {
        $videogame = $this->videoGameService->findByTitle($title);

        return new Response('Video game encontrado :' . $videogame->getTitle());
    }

    #[Route('/api/videogame/edit/{id}', name: 'edit_video_game')]
    public function update(?VideoGame $videoGame): Response
    {

        if (!$videoGame) {
            throw new NotFoundHttpException(
                "Video game nao encontrado"
            );
        }

        $videoGame->setTitle("Lara croft");
        $this->videoGameService->updateVideoGame($videoGame);

        return $this->show($videoGame->getId());
    }

    #[Route('/api/videogame/delete/{id}', name: 'delete_video_game')]
    public function delete(?VideoGame $videoGame): Response
    {
        if (!$videoGame) {
            throw new NotFoundHttpException(
                "Video game nao encontrado para deletar"
            );
        }

        $this->videoGameService->deleteVideoGame($videoGame);
        return new Response("Usuario deleteado com sucesso");
    }


}
