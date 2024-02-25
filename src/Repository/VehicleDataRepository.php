<?php

namespace App\Repository;

use App\Entity\VehicleData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<VehicleData>
 *
 * @method VehicleData|null find($id, $lockMode = null, $lockVersion = null)
 * @method VehicleData|null findOneBy(array $criteria, array $orderBy = null)
 * @method VehicleData[]    findAll()
 * @method VehicleData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VehicleData::class);
    }

    public function save(VehicleData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VehicleData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    #[ArrayShape(['data' => "array", 'recordsTotal' => "int", 'recordsFiltered' => "int"])]
    public function findUsedByDatatable(Request $request)
    {
        $qb = $this->createQueryBuilder('vd')
            ->select('vd')
        ;

        $qbTotal = $this->createQueryBuilder('vd')
            ->select('count(vd.id)')
        ;

        $query = $request->query->all();

        if($query['filterField']){
            $filter    = $query['filterField'];

            foreach ($filter as $key => $value) {
                if($value) {
                    $qb->andWhere("$key LIKE :search")
                        ->setParameter("search", "%$value%");

                    $qbTotal->andWhere("$key LIKE :search")
                        ->setParameter("search", "%$value%");
                }
            }
        }

        return $this->getDataWithFilterDatatable($qb, $qbTotal, $request, []);
    }


    /**
     * @param QueryBuilder $qb
     * @param QueryBuilder $qbTotal
     * @param $where
     * @param Request|null $request
     * @return array
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    #[ArrayShape(['data' => "array", 'recordsTotal' => "int", 'recordsFiltered' => "int"])]
    public function getDataWithFilterDatatable(QueryBuilder $qb, QueryBuilder $qbTotal, Request $request = null, $orderBy = []): array
    {
        $qbTotalFilter = clone $qbTotal;

        if ($request) {
            $query = $request->query->all();

            if (isset($query['start'], $query['length'], $query['order_by'], $query['search']['value'])) {
                $page      = $query['start'];
                $nbMaxPage = $query['length'];
                $search    = $query['search']['value'] ?? '';
                $orderBy   = $query['order_by'] ? explode(' ', $query['order_by']) : ['vd.id', 'ASC'];

                if($orderBy[0] === 'id')
                    $orderBy[0] = 'vd.id';

                $qb->setFirstResult($page)
                    ->setMaxResults($nbMaxPage)
                    ->orderBy($orderBy[0], $orderBy[1]);

//                $qbTotalFilter->andWhere($where)
//                    ->setParameter('search', "%$search%");
            }

            return [
                'data'            => array_map(function ($value) {
                    return array_values((array)$value);
                }, $qb->getQuery()->getResult()),
                'recordsTotal'    => (int)$qbTotal->getQuery()->getSingleScalarResult(),
                'recordsFiltered' => (int)$qbTotalFilter->getQuery()->getSingleScalarResult()
            ];
        }

        return $qb->getQuery()->getResult();
    }


    //    /**
    //     * @return VehicleData[] Returns an array of VehicleData objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?VehicleData
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
