<?php

namespace App\Repository;

use App\Entity\Classified;
use App\Entity\PropertyGroup;
use App\Entity\PropertyGroupOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

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

    public function findClassifiedsForSearchList(int $page, int $itemsPerPage, array $allowedPropertyGroupOptionIds, array $excludedPropertyGroupOptionIds): array
    {
        $qb = $this->createQueryBuilder('c');

        $allowedPropertyGroupOptionIds = array_map(fn (string $propertyGroupOptionId) => Uuid::fromString($propertyGroupOptionId)->toBinary(), $allowedPropertyGroupOptionIds);

        $propertyGroupOptionsIds = $this->getPropertyGroupOptionIdsToShownInSearchList();

        $query = $qb
            ->select([
                    'partial c.{id, uuid, name, description, price, offerNumber}',
                    'partial pgo.{id, uuid, name, type}',
                    'partial pgop.{id, uuid, name, type, isModel}',
                    'partial pg.{id, uuid, name}'
                ]
            )
            ->leftJoin('c.propertyGroupOptions', 'pgo', Join::WITH, $qb->expr()->andX(
                $qb->expr()->in('pgo.id', $propertyGroupOptionsIds)
            ))
            ->leftJoin('pgo.parent', 'pgop')
            ->leftJoin('pgo.propertyGroup', 'pg');

        $classifiedIds = $this->getClassifiedIdsForPropertyGroupOptionIds($allowedPropertyGroupOptionIds, $excludedPropertyGroupOptionIds);
        $query->andWhere('c.id IN (:classifiedIds)');
        $query->setParameter('classifiedIds', $classifiedIds);

        $paginatorQuery = $this->getEntityManager()
            ->createQuery($query->getDQL())
            ->setParameters($query->getParameters())
            ->setHydrationMode(AbstractQuery::HYDRATE_ARRAY)
            ->setFirstResult(($page * $itemsPerPage) - $itemsPerPage)
            ->setMaxResults($itemsPerPage);

        $paginator = new Paginator($paginatorQuery);

        $hydratedClassifieds = [];

        foreach ($paginator as $classified) {
            $hydratedClassifieds[] = $classified;
        }

        return $hydratedClassifieds;
    }

    public function getPropertyGroups(array $propertyGroupOptionIds): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $uuids = [];
        foreach ($propertyGroupOptionIds as $propertyGroupOptionId) {
            $uuids[] = Uuid::fromString($propertyGroupOptionId)->toBinary();
        }

        return $qb->select([
                'pg',
                'pgo',
                'pgp',
            ])
            ->from(PropertyGroup::class, 'pg')
            ->innerJoin('pg.groupOptions', 'pgo')
            ->leftJoin('pgo.parent', 'pgp')
            ->where($qb->expr()->in('pgo.uuid', ':propertyGroupOptionIds'))
            ->setParameter('propertyGroupOptionIds', $uuids)
            //->groupBy('pgo.id')
            ->getQuery()
            ->getArrayResult();
    }

    public function getPropertyGroupOptionIds(string $propertyGroupId, array $propertyGroupOptionNames): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $rows = $qb
            ->select(['pgo.uuid'])
            ->from(PropertyGroupOption::class, 'pgo')
            ->innerJoin('pgo.propertyGroup', 'pg')
            ->where('pg.uuid = :propertyGroupId')
            ->andWhere($qb->expr()->in('pgo.name', ':propertyGroupOptionNames'))
            ->setParameter('propertyGroupId', Uuid::fromString($propertyGroupId)->toBinary())
            ->setParameter('propertyGroupOptionNames', $propertyGroupOptionNames)
            ->getQuery()
            ->getArrayResult();

        $ids = [];
        foreach ($rows as $row) {
            if (!isset($row['uuid'])) {
                continue;
            }

            $ids[] = Uuid::fromString($row['uuid'])->toBinary();
        }

        return $ids;
    }

    public function getPropertyGroupOptionIdsForRange(string $propertyGroupId, string $parentId, ?int $from, ?int $to): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb
            ->select(['pgo.uuid'])
            ->from(PropertyGroupOption::class, 'pgo')
            ->innerJoin('pgo.propertyGroup', 'pg')
            ->innerJoin('pgo.parent', 'pgp')
            ->where('pg.uuid = :propertyGroupId')
            ->andWhere('pgp.uuid = :parentId')
            ->setParameter('propertyGroupId', Uuid::fromString($propertyGroupId)->toBinary())
            ->setParameter('parentId', Uuid::fromString($parentId)->toBinary());

        if ($from !== null) {
            $query->andWhere($qb->expr()->gte('pgo.name', ':betweenFrom'));
            $query->setParameter(':betweenFrom', $from);
        }

        if ($to !== null) {
            $query->andWhere($qb->expr()->lte('pgo.name', ':betweenTo'));
            $query->setParameter(':betweenTo', $to);
        }

        $ids = [];
        foreach ($query->getQuery()->getArrayResult() as $row) {
            if (!isset($row['uuid'])) {
                continue;
            }

            $ids[] = Uuid::fromString($row['uuid'])->toBinary();
        }

        return $ids;
    }

    public function getExcludedPropertyGroupOptionIds(string $propertyGroupId, array $whitelistPropertyGroupOptionIds): array
    {
        $whitelistPropertyGroupOptionIds = array_map(fn (string $propertyGroupOptionId) => Uuid::fromString($propertyGroupOptionId)->toBinary(), $whitelistPropertyGroupOptionIds);

        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb
            ->select(['pgo.uuid'])
            ->from(PropertyGroupOption::class, 'pgo')
            ->innerJoin('pgo.propertyGroup', 'pg')
            ->where($qb->expr()->eq('pgo.isModel', true))
            ->andWhere($qb->expr()->eq('pg.uuid', ':propertyGroupId'))
            ->andWhere($qb->expr()->notIn('pgo.uuid', ':whitelistPropertyGroupOptionIds'))
            ->setParameter('propertyGroupId', Uuid::fromString($propertyGroupId)->toBinary())
            ->setParameter('whitelistPropertyGroupOptionIds', $whitelistPropertyGroupOptionIds);

        $ids = [];
        foreach ($query->getQuery()->getArrayResult() as $row) {
            if (!isset($row['uuid'])) {
                continue;
            }

            $ids[] = Uuid::fromString($row['uuid'])->toBinary();
        }

        return $ids;
    }

    public function getExcludedPropertyGroupOptionIdsByParentId(string $parentId, array $whitelistPropertyGroupOptionIds): array
    {
        $whitelistPropertyGroupOptionIds = array_map(fn (string $propertyGroupOptionId) => Uuid::fromString($propertyGroupOptionId)->toBinary(), $whitelistPropertyGroupOptionIds);

        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb
            ->select(['pgo.uuid'])
            ->from(PropertyGroupOption::class, 'pgo')
            ->innerJoin('pgo.parent', 'pgop')
            ->where($qb->expr()->eq('pgop.uuid', ':parentId'))
            ->andWhere($qb->expr()->notIn('pgo.uuid', ':whitelistPropertyGroupOptionIds'))
            ->setParameter('parentId', Uuid::fromString($parentId)->toBinary())
            ->setParameter('whitelistPropertyGroupOptionIds', $whitelistPropertyGroupOptionIds);

        $ids = [];
        foreach ($query->getQuery()->getArrayResult() as $row) {
            if (!isset($row['uuid'])) {
                continue;
            }

            $ids[] = Uuid::fromString($row['uuid'])->toBinary();
        }

        return $ids;
    }

    public function getExcludedPropertyGroupOptionIdsForEquipment(string $propertyGroupId, array $whitelistPropertyGroupOptionIds): array
    {
        $whitelistPropertyGroupOptionIds = array_map(fn (string $propertyGroupOptionId) => Uuid::fromString($propertyGroupOptionId)->toBinary(), $whitelistPropertyGroupOptionIds);

        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb
            ->select(['pgo.uuid'])
            ->from(PropertyGroupOption::class, 'pgo')
            ->innerJoin('pgo.propertyGroup', 'pg')
            ->andWhere($qb->expr()->eq('pg.uuid', ':propertyGroupId'))
            ->andWhere($qb->expr()->notIn('pgo.uuid', ':whitelistPropertyGroupOptionIds'))
            ->andWhere($qb->expr()->isNotNull('pgo.parent'))
            ->setParameter('propertyGroupId', Uuid::fromString($propertyGroupId)->toBinary())
            ->setParameter('whitelistPropertyGroupOptionIds', $whitelistPropertyGroupOptionIds);

        $ids = [];
        foreach ($query->getQuery()->getArrayResult() as $row) {
            if (!isset($row['uuid'])) {
                continue;
            }

            $ids[] = Uuid::fromString($row['uuid'])->toBinary();
        }

        return $ids;
    }

    public function getPropertyGroupOptionIdsByParentId(string $propertyGroupId, string $parentId): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb
            ->select(['pgo.uuid'])
            ->from(PropertyGroupOption::class, 'pgo')
            ->innerJoin('pgo.propertyGroup', 'pg')
            ->innerJoin('pgo.parent', 'pgp')
            ->andWhere($qb->expr()->eq('pg.uuid', ':propertyGroupId'))
            ->andWhere($qb->expr()->eq('pgp.uuid', ':parentId'))
            ->setParameter('propertyGroupId', Uuid::fromString($propertyGroupId)->toBinary())
            ->setParameter('parentId', Uuid::fromString($parentId)->toBinary());

        $ids = [];
        foreach ($query->getQuery()->getArrayResult() as $row) {
            if (!isset($row['uuid'])) {
                continue;
            }

            $ids[] = Uuid::fromString($row['uuid'])->toBinary();
        }

        return $ids;
    }

    public function getPropertyGroupOptionIdsByGroupId(string $propertyGroupId): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb
            ->select(['pgo.uuid'])
            ->from(PropertyGroupOption::class, 'pgo')
            ->innerJoin('pgo.propertyGroup', 'pg')
            ->andWhere($qb->expr()->eq('pg.uuid', ':propertyGroupId'))
            ->andWhere($qb->expr()->isNotNull('pgo.parent'))
            ->setParameter('propertyGroupId', Uuid::fromString($propertyGroupId)->toBinary());

        $ids = [];
        foreach ($query->getQuery()->getArrayResult() as $row) {
            if (!isset($row['uuid'])) {
                continue;
            }

            $ids[] = Uuid::fromString($row['uuid'])->toBinary();
        }

        return $ids;
    }

    public function getPropertyGroupId(string $name): string
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        try {
            return (string)$qb->select(['pg.uuid'])
                ->from(PropertyGroup::class, 'pg')
                ->where($qb->expr()->eq('pg.name', ':name'))
                ->setParameter('name', $name)
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException | NoResultException) {
            return '';
        }
    }

    public function getPropertyGroupOptionId(string $name): string
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        try {
            return (string)$qb->select(['pgo.uuid'])
                ->from(PropertyGroupOption::class, 'pgo')
                ->where($qb->expr()->eq('pgo.name', ':name'))
                ->setParameter('name', $name)
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException | NoResultException) {
            return '';
        }
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

    private function getClassifiedIdsForPropertyGroupOptionIds(array $allowedPropertyGroupOptionIds, array $excludedPropertyGroupOptionIds): array
    {
        $qb = $this->createQueryBuilder('c');

        $notInClassifiedsQuery = $this->getNotInClassifiedsQuery($excludedPropertyGroupOptionIds);
        $excludedPropertyGroupOptionIds = $notInClassifiedsQuery->getParameter('excludedPropertyGroupOptionIds')->getValue();

        $query = $qb
            ->innerJoin('c.propertyGroupOptions', 'pgo')
            ->select(['c.id'])
            ->groupBy('c.id');

        if ($allowedPropertyGroupOptionIds) {
            $query->andWhere($qb->expr()->in('pgo.uuid', ':propertyGroupOptionIds'));
            $query->setParameter('propertyGroupOptionIds', $allowedPropertyGroupOptionIds);
        }

        if ($excludedPropertyGroupOptionIds) {
            $query->andWhere($qb->expr()->notIn('c.id', $notInClassifiedsQuery->getDQL()));
            $query->setParameter('excludedPropertyGroupOptionIds', $excludedPropertyGroupOptionIds);
        }

        $classifiedIds = [];
        foreach ($query->getQuery()->getArrayResult() as $row) {
            $classifiedIds[] = $row['id'] ?? null;
        }

        return $classifiedIds;
    }

    private function getNotInClassifiedsQuery(array $excludedPropertyGroupOptionIds): Query
    {
        $qb = $this->createQueryBuilder('nic');

        return $qb
            ->select(['nic.id'])
            ->innerJoin('nic.propertyGroupOptions', 'nicpgo')
            ->where($qb->expr()->in('nicpgo.uuid', ':excludedPropertyGroupOptionIds'))
            ->setParameter('excludedPropertyGroupOptionIds', $excludedPropertyGroupOptionIds)
            ->getQuery();
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
