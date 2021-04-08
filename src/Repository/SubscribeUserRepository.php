<?php

namespace App\Repository;

use App\Entity\SubscribeUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubscribeUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubscribeUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubscribeUser[]    findAll()
 * @method SubscribeUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscribeUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubscribeUser::class);
    }

    // /**
    //  * @return SubscribeUser[] Returns an array of SubscribeUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SubscribeUser
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
