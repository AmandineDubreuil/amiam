<?php

namespace App\Repository;

use App\Entity\RecetteCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RecetteCategorie>
 *
 * @method RecetteCategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecetteCategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecetteCategorie[]    findAll()
 * @method RecetteCategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecetteCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecetteCategorie::class);
    }

//    /**
//     * @return RecetteCategorie[] Returns an array of RecetteCategorie objects
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

//    public function findOneBySomeField($value): ?RecetteCategorie
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
