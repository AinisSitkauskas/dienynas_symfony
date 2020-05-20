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
}
