<?php

namespace App\Repository;

use App\Entity\PropertyGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PropertyGroup>
 *
 * @method PropertyGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method PropertyGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method PropertyGroup[]    findAll()
 * @method PropertyGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyGroupRepository extends ServiceEntityRepository
{
    private const PROPERTY_GROUP_EQUIPMENT = 'Ausstattung';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PropertyGroup::class);
    }

    public function save(PropertyGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PropertyGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getPropertyGroups(): array
    {
        $qb = $this->createQueryBuilder('pg');

        $qb = $qb
            ->select([
                'partial pg.{id, uuid, name, isEquipmentGroup}',
                'partial pgo.{id, uuid, name, type}',
                'pgop',
                'pgoc',
                'pgoch'
            ])
            ->leftJoin('pg.groupOptions', 'pgo', Join::WITH, $qb->expr()->andX(
                $qb->expr()->eq('pgo.showInSearchList', '1')
            ))
            ->leftJoin('pgo.parent', 'pgop')
            ->leftJoin('pgo.children', 'pgoc')
            ->leftJoin('pgoc.children', 'pgoch');

        return $qb->getQuery()->getArrayResult();
    }

    public function getPropertyGroupForEquipment(): array
    {
        $qb = $this->createQueryBuilder('pg');

        $qb = $qb
            ->select([
                'partial pg.{id, uuid, name, isEquipmentGroup}',
                'partial pgo.{id, uuid, name, type}',
                'pgop',
                'pgoc',
                'pgoch'
            ])
            ->leftJoin('pg.groupOptions', 'pgo', Join::WITH)
            ->leftJoin('pgo.parent', 'pgop')
            ->leftJoin('pgo.children', 'pgoc')
            ->leftJoin('pgoc.children', 'pgoch')
            ->where($qb->expr()->eq('pg.name', ':propertyGroupNameEquipment'))
            ->setParameter('propertyGroupNameEquipment', self::PROPERTY_GROUP_EQUIPMENT);

        return $qb->getQuery()->getArrayResult();
    }

//    /**
//     * @return Property[] Returns an array of Property objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Property
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
