<?php

namespace App\Controller;

use App\Entity\Platform;
use App\Entity\VideoGame;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TestGameController extends AbstractController
{
    #[Route('/test-many-to-many', name: 'test_many_to_many')]
    public function index(
        EntityManagerInterface $em,
    ): Response {
        $pc = (new Platform())->setName('PC');

        $game = (new VideoGame())
            ->setTitle('Elden Ring')
            ->setGenre("RPG")
            ->setDeveloper("FROM SOFTWARE")
            ->setReleaseDate(new \DateTime('2020-01-01'));

        $game->addPlatform($pc);

        $em->persist($pc);
        $em->persist($game);
        $em->flush();

        return new Response('Relacionamento ManyToMany testado com sucesso!');
    }
}
