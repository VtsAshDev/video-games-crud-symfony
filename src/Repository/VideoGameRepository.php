<?php

namespace App\Repository;

use App\Entity\VideoGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

use function Symfony\Component\String\s;

/**
 * @extends ServiceEntityRepository<VideoGame>
 */
class VideoGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoGame::class);
    }

    public function beginTransaction(): void
    {
        $this->getEntityManager()->beginTransaction();
    }

    public function commit(): void
    {
        $this->getEntityManager()->commit();
    }

    public function rollback(): void
    {
        $this->getEntityManager()->rollback();
    }

    /**
     * @throws \Exception
     */
    public function save(VideoGame $videoGame): void
    {
        $this->getEntityManager()->persist($videoGame);
        $this->getEntityManager()->flush();
    }

    public function findVideoGameByName(string $title): ?VideoGame
    {
        $title = strtolower(trim($title));

        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('v')
            ->from(VideoGame::class, 'v')
            ->where('LOWER(v.title) LIKE :title')
            ->setParameter('title', '%' . $title . '%')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function updateVideoGame($videoGame): void
    {
        $this->getEntityManager()->persist($videoGame);
        $this->getEntityManager()->flush();
    }

    public function deleteVideoGame($videoGame): void
    {
        $this->getEntityManager()->remove($videoGame);
        $this->getEntityManager()->flush();
    }
}
