<?php

namespace App\Repository;

use App\Entity\SousGroupeAli;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SousGroupeAli>
 *
 * @method SousGroupeAli|null find($id, $lockMode = null, $lockVersion = null)
 * @method SousGroupeAli|null findOneBy(array $criteria, array $orderBy = null)
 * @method SousGroupeAli[]    findAll()
 * @method SousGroupeAli[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SousGroupeAliRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SousGroupeAli::class);
    }

//    /**
//     * @return SousGroupeAli[] Returns an array of SousGroupeAli objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SousGroupeAli
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
