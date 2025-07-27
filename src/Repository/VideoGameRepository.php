<?php

namespace App\Repository;

use App\Entity\VideoGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VideoGame>
 */
class VideoGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoGame::class);
    }

    /**
     * @throws Exception
     */
    public function findVideoGameByName(string $title): array
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM video_game v
            WHERE v.title LIKE :title
            ';

        return $conn->executeQuery($sql, ['title' => strtolower(trim($title))])->fetchAssociative();
    }


}
