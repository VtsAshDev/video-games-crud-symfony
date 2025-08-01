<?php

namespace App\Service;

use App\Contracts\DeleteVideoGameInterface;
use App\Entity\VideoGame;
use App\Mapper\VideoGameMapper;
use App\Repository\VideoGameRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Exception;
readonly class DeleteVideoGameService implements DeleteVideoGameInterface
{
    public function __construct(
        private VideoGameRepository $videoGameRepository,
    ) {
    }

    public function __invoke(VideoGame $videoGame): void
    {
        try {
            $this->videoGameRepository->beginTransaction();
            $this->videoGameRepository->deleteVideoGame($videoGame);
            $this->videoGameRepository->commit();
        } catch (Exception $e) {
            throw new BadRequestHttpException("Video game nao DELETADO");
        }
    }
}
