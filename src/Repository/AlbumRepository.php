<?php

namespace App\Repository;

use App\Entity\Album;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Album>
 *
 * @method Album|null find($id, $lockMode = null, $lockVersion = null)
 * @method Album|null findOneBy(array $criteria, array $orderBy = null)
 * @method Album[]    findAll()
 * @method Album[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Album::class);
    }

    public function add(Album $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Album $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Album[] Returns an array of Album objects
    */
    public function listeAlbumsComplete(): ?Query
   {
       return $this->createQueryBuilder('a')
            ->select('a','s','art','m')
            ->innerjoin('a.styles','s')
            ->innerjoin('a.artiste','art')
            ->innerjoin('a.morceaux','m')
            ->orderBy('a.id', 'ASC')
            ->getQuery()
       ;
   }
 
       /**
     * @return Query[] Returns an array of Style objects
     */
     public function listeAlbumsCompletePaginee():Query
     {
         return $this->createQueryBuilder('alb')
             ->select('alb','art','stl','mrc')
             ->leftJoin('alb.artiste','art')
             ->leftJoin('alb.styles','stl')
             ->leftJoin('alb.morceaux','mrc')
             ->orderBy('alb.id', 'DESC')
             ->getQuery()
         ;
     }

//    public function findOneBySomeField($value): ?Album
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
