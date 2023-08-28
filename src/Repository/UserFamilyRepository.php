<?php

namespace App\Repository;

use App\Entity\UserFamily;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserFamily>
 *
 * @method UserFamily|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserFamily|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserFamily[]    findAll()
 * @method UserFamily[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserFamilyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFamily::class);
    }

//    /**
//     * @return UserFamily[] Returns an array of UserFamily objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserFamily
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
