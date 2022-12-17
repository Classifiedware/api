<?php

namespace App\Repository;

use App\Entity\ClassifiedPropertyGroupOptionValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClassifiedPropertyGroupOptionValue>
 *
 * @method ClassifiedPropertyGroupOptionValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClassifiedPropertyGroupOptionValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClassifiedPropertyGroupOptionValue[]    findAll()
 * @method ClassifiedPropertyGroupOptionValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassifiedPropertyGroupOptionValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClassifiedPropertyGroupOptionValue::class);
    }

    public function save(ClassifiedPropertyGroupOptionValue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ClassifiedPropertyGroupOptionValue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ClassifiedPropertyGroupOption[] Returns an array of ClassifiedPropertyGroupOption objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ClassifiedPropertyGroupOption
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
