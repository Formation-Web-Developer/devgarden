<?php

namespace App\Repository;

use App\Entity\Category;
use App\Utils\State;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getCategories()
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.slug is not NULL')
            ->getQuery()
            ->getResult();
    }

    public function getCategoriesLimit(ResourceRepository $repository)
    {
        $results = $this->createQueryBuilder('c')
            ->select('c')
            ->addSelect('('.
                $repository->createQueryBuilder('r')
                    ->select('COUNT(r.id)')
                    ->where('r.category = c.id')
                    ->andWhere('r.validation = :validation')
                    ->getQuery()
                    ->getDQL()
            .') AS count')
            ->where('c.slug is not NULL')
            ->orderBy('count', 'DESC')
            ->setMaxResults(10)
            ->setParameter('validation', State::VALIDATED)
            ->getQuery()
            ->getResult();

        $categories = [];
        foreach ($results as $result) {
            $categories[] = $result[0];
        }
        return $categories;
    }

    public function getWaitingCategories()
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.slug is NULL')
            ->getQuery()
            ->getResult();
    }

     /**
      * @return Category[] Returns an array of Category objects
      */
    public function search($search): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.name LIKE :val')
            ->setParameter('val', '%'.$search.'%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
