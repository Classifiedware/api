<?php

namespace App\Repository;

use App\Entity\AdminAccessToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdminAccessToken>
 *
 * @method AdminAccessToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminAccessToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminAccessToken[]    findAll()
 * @method AdminAccessToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminAccessTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminAccessToken::class);
    }

    //    /**
    //     * @return Admin[] Returns an array of AdminAccessToken objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AdminAccessToken
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
