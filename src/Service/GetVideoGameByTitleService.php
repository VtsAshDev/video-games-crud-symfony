<?php

namespace App\Service;

use App\Contracts\GetVideoGameByTitleInterface;
use App\Mapper\VideoGameMapper;
use App\Repository\VideoGameRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;

readonly class  GetVideoGameByTitleService implements GetVideoGameByTitleInterface
{
    public function __construct(
        private VideoGameRepository $videoGameRepository,
        private VideoGameMapper $videoGameMapper,
        private SerializerInterface $serializer
    ) {
    }

    public function __invoke(string $title): string
    {
        $videoGame = $this->videoGameRepository->findVideoGameByName($title);
        if (!$videoGame) {
            throw new NotFoundHttpException("Video game nao encontrado");
        }

        $dto = $this->videoGameMapper->toDto($videoGame);

        return $this->serializer->serialize($dto, 'json');
    }
}
