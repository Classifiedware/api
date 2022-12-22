<?php

namespace App\Repository;

use App\Entity\Classified;
use App\Entity\PropertyGroupOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Classified>
 *
 * @method Classified|null find($id, $lockMode = null, $lockVersion = null)
 * @method Classified|null findOneBy(array $criteria, array $orderBy = null)
 * @method Classified[]    findAll()
 * @method Classified[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassifiedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Classified::class);
    }

    public function save(Classified $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Classified $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findClassifiedsForSearchList(int $page, $itemsPerPage, array $propertyGroupOptionValueIds = []): array
    {
        $qb = $this->createQueryBuilder('c');

        $propertyGroupOptionsIds = $this->getPropertyGroupOptionIdsToShownInSearchList();

        $query = $qb
            ->select([
                    'partial c.{id, name, description, price}',
                    'partial pgov.{id, value}',
                    'partial gp.{id, name}'
                ]
            )
            ->leftJoin('c.propertyGroupOptionValues', 'pgov', Join::WITH, $qb->expr()->andX(
                $qb->expr()->in('pgov.groupOption', $propertyGroupOptionsIds)
            ))
            ->leftJoin('pgov.groupOption', 'gp');

        if ($propertyGroupOptionValueIds) {
            // todo filter
        }

        $paginator = new Paginator($query->getQuery());
        $paginator->getQuery()
            ->setFirstResult(($page * $itemsPerPage) - $itemsPerPage)
            ->setMaxResults($itemsPerPage);

        return $paginator->getQuery()->getArrayResult();
    }

    private function getPropertyGroupOptionIdsToShownInSearchList(): array
    {
        $rows = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select(['po.id'])
            ->from(PropertyGroupOption::class, 'po')
            ->where('po.showInSearchList = 1')
            ->getQuery()
            ->getArrayResult();

        $ids = [];
        foreach ($rows as $row) {
            $ids[] = $row['id'] ?? null;
        }

        return $ids;
    }

//    /**
//     * @return Classified[] Returns an array of Classified objects
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

//    public function findOneBySomeField($value): ?Classified
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
