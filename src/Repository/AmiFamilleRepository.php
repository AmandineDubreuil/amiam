<?php

namespace App\Repository;

use App\Entity\AmiFamille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AmiFamille>
 *
 * @method AmiFamille|null find($id, $lockMode = null, $lockVersion = null)
 * @method AmiFamille|null findOneBy(array $criteria, array $orderBy = null)
 * @method AmiFamille[]    findAll()
 * @method AmiFamille[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AmiFamilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AmiFamille::class);
    }

//    /**
//     * @return AmiFamille[] Returns an array of AmiFamille objects
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

//    public function findOneBySomeField($value): ?AmiFamille
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
