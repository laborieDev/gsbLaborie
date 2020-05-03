<?php

namespace App\Repository;

use App\Entity\SaveLoginUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SaveLoginUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method SaveLoginUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method SaveLoginUser[]    findAll()
 * @method SaveLoginUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaveLoginUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SaveLoginUser::class);
    }

    // /**
    //  * @return SaveLoginUser[] Returns an array of SaveLoginUser objects
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
    public function findOneBySomeField($value): ?SaveLoginUser
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
