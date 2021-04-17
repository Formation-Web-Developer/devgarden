<?php

namespace App\Repository;

use App\Entity\Resource;
use App\Utils\State;
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

    public function resourceTopLimit(SubscribeResourceRepository $repository, int $limit): array
    {
        $results = $this->createQueryBuilder('r')
            ->select('r')
            ->addSelect('('.
                $repository->createQueryBuilder('sr')
                    ->select('COUNT(sr.id)')
                    ->where('sr.resource=r.id')
                    ->getQuery()
                    ->getDQL()
                .') AS count')
            ->setMaxResults($limit)
            ->where('r.validation = :validation')
            ->orderBy('count', 'DESC')
            ->setParameter('validation', State::VALIDATED)
            ->getQuery()
            ->getResult();

        $resources = [];
        foreach ($results as $result) {
            $resources[] = $result[0];
        }
        return $resources;
    }

    public function resourceNewLimit(int $limit): array
    {
        return $this->createQueryBuilder('r')
            ->select('r')
            ->setMaxResults($limit)
            ->where('r.validation = :validation')
            ->setParameter('validation', State::VALIDATED)
            ->orderBy('r.created_at', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }


    public function getResourceByState(int $state)
    {
        return $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.validation = :validation')
            ->setParameter('validation', $state)
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
            ->andWhere('r.validation = :validation')
            ->setParameters([
                'category_slug' => $categorySlug,
                'resource_slug' => $resourceSlug,
                'validation'    => State::VALIDATED
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Resource[]
     */
    function getResourceByCategoryLimit(string $categorySlug, int $limit): array
    {
        return $this->createQueryBuilder('r')
            ->select('r', 'c')
            ->join('r.category', 'c')
            ->where('c.slug = :slug')
            ->andwhere('r.validation = :validation')
            ->setMaxResults($limit)
            ->setParameters([
                'slug' => $categorySlug,
                'validation' => State::VALIDATED
            ])
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Resource[]
     */
    function getNewResourceByCategoryLimit(string $categorySlug, int $limit): array
    {
        return $this->createQueryBuilder('r')
            ->select('r', 'c')
            ->join('r.category', 'c')
            ->where('c.slug = :slug')
            ->andWhere('r.validation = :validation')
            ->setMaxResults($limit)
            ->orderBy('r.created_at', 'DESC')
            ->setParameters([
                'slug' => $categorySlug,
                'validation' => State::VALIDATED
            ])
            ->getQuery()
            ->getResult();
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
