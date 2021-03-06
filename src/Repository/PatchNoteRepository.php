<?php

namespace App\Repository;

use App\Entity\PatchNote;
use App\Entity\Resource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

/**
 * @method PatchNote|null find($id, $lockMode = null, $lockVersion = null)
 * @method PatchNote|null findOneBy(array $criteria, array $orderBy = null)
 * @method PatchNote[]    findAll()
 * @method PatchNote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatchNoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PatchNote::class);
    }

    public function getByResourceAndCategorySlug(
        string $categorySlug,
        string $resourceSlug,
        string $patchNoteSlug
    ): ?PatchNote
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'r', 'c', 'u')
            ->join('p.resource', 'r')
            ->join('r.category', 'c')
            ->join('r.user', 'u')
            ->where('p.slug = :patch_note_slug')
            ->andWhere('r.slug = :resource_slug')
            ->andWhere('c.slug = :category_slug')
            ->setParameters([
                'patch_note_slug' => $patchNoteSlug,
                'resource_slug'   => $resourceSlug,
                'category_slug'   => $categorySlug
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function updateLatest(PatchNote $patchNote)
    {
        return $this->createQueryBuilder('p')
            ->update()
            ->set('p.latest', ('(CASE p.id WHEN :pid THEN 1 ELSE 0 END)'))
            ->where('p.resource = :id')
            ->setParameters([
                'id' => $patchNote->getResource()->getId(),
                'pid'=> $patchNote->getId()
            ])
            ->getQuery()
            ->execute()
        ;
    }

    // /**
    //  * @return PatchNote[] Returns an array of PatchNote objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PatchNote
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
