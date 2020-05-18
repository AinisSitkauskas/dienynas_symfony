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

    /**
     * @param int $studentId
     * @param int $teachingSubjectId
     * @return float
     */
    public function getAverageMarks($studentId, $teachingSubjectId)
    {
        return $this->createQueryBuilder('m')
            ->select("avg(m.mark) as average_mark")
            ->andWhere('m.fkStudent = :studentId')
            ->andWhere('m.fkTeachingSubject = :teachingSubjectId')
            ->setParameter('studentId', $studentId)
            ->setParameter('teachingSubjectId', $teachingSubjectId)
            ->getQuery()
            ->getSingleResult()
            ;
    }

    /**
     * @return array
     */
    public function getTopStudents()
    {
        return $this->createQueryBuilder('m')
            ->select("u.id, u.name, u.surname, avg(m.mark) as averageMark")
            ->join('m.fkStudent', 'u', 'WITH',  'm.fkStudent = u.id')
            ->groupBy('u.id')
            ->having('averageMark >= 9')
            ->orderBy('averageMark','DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }
}
