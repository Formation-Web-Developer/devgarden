<?php

namespace App\Repository;

use App\Entity\SubscribeResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubscribeResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubscribeResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubscribeResource[]    findAll()
 * @method SubscribeResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscribeResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubscribeResource::class);
    }

    // /**
    //  * @return SubscribeResource[] Returns an array of SubscribeResource objects
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
    public function findOneBySomeField($value): ?SubscribeResource
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
