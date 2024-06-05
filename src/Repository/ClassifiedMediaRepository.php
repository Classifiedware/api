<?php

namespace App\Repository;

use App\Entity\ClassifiedMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClassifiedMedia>
 *
 * @method ClassifiedMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClassifiedMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClassifiedMedia[]    findAll()
 * @method ClassifiedMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassifiedMediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClassifiedMedia::class);
    }

    public function save(ClassifiedMedia $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ClassifiedMedia $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
