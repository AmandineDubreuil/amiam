<?php

namespace App\Repository;

use App\Entity\Ami;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ami>
 *
 * @method Ami|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ami|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ami[]    findAll()
 * @method Ami[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AmiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ami::class);
    }

//    /**
//     * @return Ami[] Returns an array of Ami objects
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

//    public function findOneBySomeField($value): ?Ami
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findByFamille($familleId): array
{
    return $this->createQueryBuilder('r')
           ->andWhere('r.famille = :val')
           ->setParameter('val', $familleId)
           ->orderBy('r.id', 'ASC')
       //    ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
}
}
