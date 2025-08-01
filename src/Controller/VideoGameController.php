<?php

namespace App\Controller;

use App\Contracts\CreateVideoGameInterface;
use App\Contracts\DeleteVideoGameInterface;
use App\Contracts\GetVideoGameByIdInterface;
use App\Contracts\GetVideoGameByTitleInterface;
use App\Contracts\UpdateVideoGameInterface;
use App\Dto\CreateVideoGameDto;
use App\Entity\Platform;
use App\Entity\VideoGame;
use App\Service\GetVideoGamesByPlatformService;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[OA\Tag(name: 'VideoGames')]
readonly class VideoGameController
{
    public function __construct(
        private CreateVideoGameInterface $createVideoGameService,
        private GetVideoGameByIdInterface $getVideoGameById,
        private GetVideoGameByTitleInterface $getVideoGameByTitle,
        private UpdateVideoGameInterface $updateVideoGame,
        private DeleteVideoGameInterface $deleteVideoGame,
        private GetVideoGamesByPlatformService $getVideoGamesByPlatform,
    ) {
    }


    #[Route('/api/videogame', name: 'create_video_game', methods: ['POST'])]
    #[OA\Post(
        path: '/api/videogame',
        summary: 'Rota De criacao de VideoGame',
        responses: [
            new OA\Response(
                response: 201,
                description: 'Criado com sucesso',
            )
        ]
    )]
    public function createVideoGame(
        #[MapRequestPayload] CreateVideoGameDto $videoGameDto
    ): Response {

        try {
            ($this->createVideoGameService)($videoGameDto);
        } catch (Throwable $exception) {
            return new JsonResponse(["message"=>"Nao foi possivel criar o videogame"], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(["message"=>"Video game Criado com sucesso"], Response::HTTP_CREATED);
    }


    #[Route('/api/videogame/{id}', name: 'show_video_game', methods: ['GET'])]
    #[OA\Get(
        path: '/api/videogame/{id}',
        summary: 'Rota para pesquisa de VideoGame Por ID',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Video Game Encontrado com sucesso',
            )
        ]
    )]
    public function show(int $id): Response
    {
        try {
            $videoGame = ($this->getVideoGameById)($id);

            return new JsonResponse($videoGame, Response::HTTP_OK,json: true);
        } catch (Throwable $exception) {
           return new JsonResponse(["message"=>"Nenhum Video Game encontrado"], Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('/api/videogame/title/{title}', name: 'show_title', methods: ['GET'])]
    #[OA\Get(
        path: '/api/videogame/title/{title}',
        summary: 'Rota para pesquisa de VideoGame Por Title',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Video Game Encontrado com sucesso',
            )
        ]
    )]
    public function showByTitle(string $title): Response
    {
        try {
            $videoGame = ($this->getVideoGameByTitle)($title);

            return new JsonResponse($videoGame, Response::HTTP_OK,json: true);
        } catch (Throwable $exception) {
           return new JsonResponse(["message"=>"Nenhum Video Game encontrado"], Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('/api/videogame/edit/{id}', name: 'edit_video_game', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/videogame/edit/{id}',
        summary: 'Rota para Edição de VideoGame',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Video Game Editado com sucesso',
            )
        ]
    )]
    public function update(
        ?VideoGame $videoGame,
       #[MapRequestPayload] CreateVideoGameDto $videoGameDto
    ): Response {

        if (!$videoGame) {
            return new JsonResponse([
                "message" => "Video game nao encontrado"],
                Response::HTTP_NOT_FOUND
            );
        }

        ($this->updateVideoGame)($videoGame,$videoGameDto);

        return $this->show($videoGame->getId());
    }

    #[Route('/api/videogame/delete/{id}', name: 'delete_video_game', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/videogame/delete/{id}',
        summary: 'Rota para deletar o VideoGame Por ID',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Video Game Deletado com sucesso',
            )
        ]
    )]
    public function delete(?VideoGame $videoGame): Response
    {
        if (!$videoGame) {
            return new JsonResponse(
                ['message' => 'Video game não encontrado para deletar'],
                Response::HTTP_NOT_FOUND
            );
        }

        try {
            ($this->deleteVideoGame)($videoGame);
        } catch (Throwable $exception) {
            return new JsonResponse(
                ["message" => "Erro ao deletar o video game"],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new JsonResponse(["message" => "Video game deletado com sucesso"]);
    }


    #[Route('/api/videogame/platform/{platform}', name: 'video_game_by_platform', methods: ['GET'])]
    #[OA\Get(
        path: '/api/videogame/platform/{platform}',
        summary: 'Rota para encontrar os videogames de acordo com o id da plataforma',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Video Games Encontrados com sucesso',
            )
        ]
    )]
    public function showVideoGamesByPlatform(?Platform $platform): Response
    {
        if (!$platform) {
            return new JsonResponse(
                ['message' => 'Plataforma não encontrada'],
                Response::HTTP_NOT_FOUND
            );
        }

        try {
            $videoGames = ($this->getVideoGamesByPlatform)($platform);
            return new JsonResponse($videoGames, Response::HTTP_OK,json: true);
        } catch (Throwable $exception) {
            return new JsonResponse(
                ["message" => "Erro ao encontrar VideoGames no banco de dados"],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
