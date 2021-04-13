<?php

namespace App\Repository;

use App\Entity\Resource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Resource|null find($id, $lockMode = null, $lockVersion = null)
 * @method Resource|null findOneBy(array $criteria, array $orderBy = null)
 * @method Resource[]    findAll()
 * @method Resource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Resource::class);
    }

    // /**
    //  * @return Resource[] Returns an array of Resource objects
    //  */

    public function resourceLimitHome()
    {
        return $this->createQueryBuilder('r')
            ->select('r')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getByCategoryAndSlug(string $categorySlug, string $resourceSlug): ?\App\Entity\Resource
    {
        return $this->createQueryBuilder('r')
            ->select('r', 'c', 'u')
            ->join('r.category', 'c')
            ->join('r.user', 'u')
            ->where('r.slug = :resource_slug')
            ->andWhere('c.slug = :category_slug')
            ->setParameters([
                'category_slug' => $categorySlug,
                'resource_slug' => $resourceSlug
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }

    /*
    public function findOneBySomeField($value): ?Resource
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
