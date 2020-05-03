<?php

namespace App\Repository;

use App\Entity\FicheForfait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FicheForfait|null find($id, $lockMode = null, $lockVersion = null)
 * @method FicheForfait|null findOneBy(array $criteria, array $orderBy = null)
 * @method FicheForfait[]    findAll()
 * @method FicheForfait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheForfaitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FicheForfait::class);
    }

    // /**
    //  * @return FicheForfait[] Returns an array of FicheForfait objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FicheForfait
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
