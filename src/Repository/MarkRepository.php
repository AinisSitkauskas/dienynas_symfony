<?php

namespace App\Repository;

use App\Entity\Mark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Mark|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mark|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mark[]    findAll()
 * @method Mark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mark::class);
    }

    public function getAverageMarks($studentId, $teachingSubjectId)
    {
        return $this->createQueryBuilder('m')
            ->select("avg(m.mark) as average_mark")
            ->andWhere('m.fkStudent = :studentId')
            ->andWhere('m.fkTeachingSubject = :teachingSubjectId')
            ->setParameter('studentId', $studentId)
            ->setParameter('teachingSubjectId', $teachingSubjectId)
            ->getQuery()
            ->getResult()
            ;
    }


    // /**
    //  * @return Mark[] Returns an array of Mark objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Mark
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
