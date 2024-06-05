<?php

namespace App\Repository;

use App\Entity\MediaThumbnail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MediaThumbnail>
 *
 * @method MediaThumbnail|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaThumbnail|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaThumbnail[]    findAll()
 * @method MediaThumbnail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaThumbnailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaThumbnail::class);
    }

    public function save(MediaThumbnail $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MediaThumbnail $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
