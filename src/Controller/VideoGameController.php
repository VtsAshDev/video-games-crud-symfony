<?php

namespace App\Controller;

use App\Contracts\VideoGameServiceInterface;
use App\Dto\CreateVideoGameDto;
use App\Dto\VideoGameWithPlatformDto;
use App\Entity\VideoGame;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Json;
use Throwable;

class VideoGameController
{
    public function __construct(
        private VideoGameServiceInterface $videoGameService
    ) {
    }

    #[Route('/api/videogame', name: 'create_video_game', methods: ['POST'])]
    public function createVideoGame(
        #[MapRequestPayload] CreateVideoGameDto $videoGameDto
    ): Response {

        try {
            $this->videoGameService->create($videoGameDto);
        } catch (\Throwable $exception) {
            return new JsonResponse(["message"=>"Nao foi possivel criar o videogame"], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(["message"=>"Video game Criado com sucesso"], Response::HTTP_CREATED);
    }

    #[Route('/api/videogame/{id}', name: 'show_video_game')]
    public function show(int $id): Response
    {
        try {
            $videoGame = $this->videoGameService->findById($id);

            return new JsonResponse($videoGame, Response::HTTP_OK,json: true);
        } catch (Throwable $exception) {
           return new JsonResponse(["message"=>"Nenhum Video Game encontrado"], Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('/api/videogame/title/{title}')]
    public function showByTitle(string $title): Response
    {
        try {
            $videoGame = $this->videoGameService->findByTitle($title);

            return new JsonResponse($videoGame, Response::HTTP_OK,json: true);
        } catch (Throwable $exception) {
           return new JsonResponse(["message"=>"Nenhum Video Game encontrado"], Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('/api/videogame/edit/{id}', name: 'edit_video_game', methods: ['PUT'])]
    public function update(
        ?VideoGame $videoGame,
        #[MapRequestPayload] VideoGameWithPlatformDto $videoGameDto
    ): Response {

        if (!$videoGame) {
            throw new NotFoundHttpException(
                "Video game nao encontrado"
            );
        }

        $this->videoGameService->updateVideoGame($videoGame,$videoGameDto);

        return $this->show($videoGame->getId());
    }

    #[Route('/api/videogame/delete/{id}', name: 'delete_video_game', methods: ['DELETE'])]
    public function delete(?VideoGame $videoGame): Response
    {
        if (!$videoGame) {
            return new JsonResponse(
                ['message' => 'Video game não encontrado para deletar'],
                Response::HTTP_NOT_FOUND
            );
        }

        try {
            $this->videoGameService->deleteVideoGame($videoGame);
        } catch (Throwable $exception) {
            return new JsonResponse(
                ["message" => "Erro ao deletar o video game"],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new JsonResponse(["message" => "Video game deletado com sucesso"]);
    }


    #[Route('/api/videogame/platform/{platform}', name: 'video_game_by_platform', methods: ['GET'])]
    public function showVideoGamesByPlatform(int $platform): Response
    {
        if (!$platform) {
            return new JsonResponse(
                ['message' => 'Plataforma não encontrada'],
                Response::HTTP_NOT_FOUND
            );
        }

        try {
            $videoGames = $this->videoGameService->getVideoGamesByPlatform($platform);
            return new JsonResponse($videoGames, Response::HTTP_OK,json: true);
        } catch (Throwable $exception) {
            return new JsonResponse(
                ["message" => "Erro ao encontrar VideoGames no banco de dados"],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
