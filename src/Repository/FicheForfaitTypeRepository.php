<?php

namespace App\Repository;

use App\Entity\FicheForfaitType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FicheForfaitType|null find($id, $lockMode = null, $lockVersion = null)
 * @method FicheForfaitType|null findOneBy(array $criteria, array $orderBy = null)
 * @method FicheForfaitType[]    findAll()
 * @method FicheForfaitType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheForfaitTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FicheForfaitType::class);
    }

    // /**
    //  * @return FicheForfaitType[] Returns an array of FicheForfaitType objects
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
    public function findOneBySomeField($value): ?FicheForfaitType
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
