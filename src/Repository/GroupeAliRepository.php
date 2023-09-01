<?php

namespace App\Repository;

use App\Entity\GroupeAli;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroupeAli>
 *
 * @method GroupeAli|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupeAli|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupeAli[]    findAll()
 * @method GroupeAli[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupeAliRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupeAli::class);
    }

//    /**
//     * @return GroupeAli[] Returns an array of GroupeAli objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GroupeAli
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
