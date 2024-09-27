<?php

namespace App\Repository;

use App\Entity\Recette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recette>
 *
 * @method Recette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recette[]    findAll()
 * @method Recette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recette::class);
    }

    //    /**
    //     * @return Recette[] Returns an array of Recette objects
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

    //    public function findOneBySomeField($value): ?Recette
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


       /**
        * @return Recette[] Returns an array of Recette objects
        */
       public function findByUser($userId): array
       {
           return $this->createQueryBuilder('r')
               ->andWhere('r.user = :user')
               ->setParameter('user', $userId)
               ->orderBy('r.titre', 'ASC')
              // ->setMaxResults(10)
               ->getQuery()
               ->getResult()
           ;
       }

    public function findBySearch($searchData = null)
{
    $recettes = $this->createQueryBuilder('r');

    if ($searchData && !empty($searchData)) {
        // Si $search n'est pas vide, on ajoute une condition de recherche
        $recettes->andWhere('r.titre LIKE :titre')
           ->setParameter('titre', '%'.$searchData.'%')
           ->addOrderBy('r.categorie', 'DESC');
    }

    return $recettes->getQuery()->getResult();
}
}
