<?php

namespace App\Service;

use App\Entity\Platform;
use App\Mapper\VideoGameMapper;
use App\Repository\VideoGameRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;

readonly class GetVideoGamesByPlatformService
{
    public function __construct(
        private VideoGameRepository $videoGameRepository,
        private VideoGameMapper $videoGameMapper,
        private SerializerInterface $serializer
    ) {
    }

    public function __invoke(Platform $platform): string
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
