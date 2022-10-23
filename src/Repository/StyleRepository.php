<?php

namespace App\Repository;

use App\Entity\Style;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Style>
 *
 * @method Style|null find($id, $lockMode = null, $lockVersion = null)
 * @method Style|null findOneBy(array $criteria, array $orderBy = null)
 * @method Style[]    findAll()
 * @method Style[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StyleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Style::class);
    }

    public function add(Style $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Style $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

  /**
    * @return Style[] Returns an array of Style objects
    */
    public function listeStylesComplete()
    {
        return $this->createQueryBuilder('stl')
            ->select('stl','alb')
            ->leftJoin('stl.albums','alb')
            ->orderBy('stl.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
 
       /**
     * @return Query[] Returns an array of Style objects
     */
     public function listeStylesCompletePaginee():Query
     {
         return $this->createQueryBuilder('stl')
             ->select('stl','alb')
             ->leftJoin('stl.albums','alb')
             ->orderBy('stl.id', 'ASC')
             ->getQuery()
         ;
     }

//    public function findOneBySomeField($value): ?Style
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
