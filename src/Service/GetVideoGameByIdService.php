<?php

namespace App\Service;

use App\Contracts\GetVideoGameByIdInterface;
use App\Mapper\VideoGameMapper;
use App\Repository\VideoGameRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;

readonly class GetVideoGameByIdService implements GetVideoGameByIdInterface
{
    public function __construct(
        private VideoGameMapper $videoGameMapper,
        private VideoGameRepository $videoGameRepository,
        private SerializerInterface $serializer
    ) {
    }

    public function __invoke(int $id): string
    {
        $videoGame = $this->videoGameRepository->find($id);

        if (!$videoGame) {
            throw new NotFoundHttpException("Video game nao encontrado");
        }

        $dto = $this->videoGameMapper->toDto($videoGame);

        return $this->serializer->serialize($dto, 'json');
    }
}
