<?php

namespace App\Repository;

use App\Entity\Repas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Repas>
 *
 * @method Repas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Repas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Repas[]    findAll()
 * @method Repas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Repas::class);
    }

//    /**
//     * @return Repas[] Returns an array of Repas objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Repas
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findByUser($value): array
{
    return $this->createQueryBuilder('r')
        ->andWhere('r.user = :val')
        ->setParameter('val', $value)
        ->orderBy('r.id', 'ASC')
        ->getQuery()
        ->getResult();
}

public function findByRecette($recetteId): array
{
    return $this->createQueryBuilder('r')
           ->andWhere('r.recettes = :val')
           ->setParameter('val', $recetteId)
           ->orderBy('r.id', 'ASC')
       //    ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
}

}
