<?php

namespace App\Repository;

use App\Model\SearchData;
use App\Entity\RecetteIngredient;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<RecetteIngredient>
 *
 * @method RecetteIngredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecetteIngredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecetteIngredient[]    findAll()
 * @method RecetteIngredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecetteIngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecetteIngredient::class);
    }

//    /**
//     * @return RecetteIngredient[] Returns an array of RecetteIngredient objects
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

//    public function findOneBySomeField($value): ?RecetteIngredient
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findByRecette($recetteId): array
{
    return $this->createQueryBuilder('r')
           ->andWhere('r.recette = :val')
           ->setParameter('val', $recetteId)
           ->orderBy('r.id', 'ASC')
       //    ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
}



// public function removeIngredient(RecetteIngredient $recetteIngredient): void
// {
//     $this->getEntityManager()->remove($recetteIngredient);
//     $this->getEntityManager()->flush();
// }

}
