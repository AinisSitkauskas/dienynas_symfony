<?php

namespace App\Repository;

use App\Entity\TeachingSubject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TeachingSubject|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachingSubject|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachingSubject[]    findAll()
 * @method TeachingSubject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachingSubjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachingSubject::class);
    }

    // /**
    //  * @return TeachingSubject[] Returns an array of TeachingSubject objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TeachingSubject
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
