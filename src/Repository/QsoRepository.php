<?php

namespace App\Repository;

use App\Entity\Qso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Qso>
 */
class QsoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Qso::class);
    }

    public function getWorkedStateCounts(): array
    {
        return $this->createQueryBuilder('q')
            ->select('q.state AS state')
            ->addSelect('COUNT(q.id) AS qsoCount')
            ->groupBy('q.state')
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return Qso[] Returns an array of Qso objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Qso
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
