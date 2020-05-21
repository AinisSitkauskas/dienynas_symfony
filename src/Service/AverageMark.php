<?php

namespace App\Service;

use App\Entity\Mark;
use App\Repository\MarkRepository;
use App\Repository\TeachingSubjectRepository;
use App\Repository\UserRepository;

class AverageMark
{
    private const ADMIN_USERNAME = 'admin';

    /**
     * @var MarkRepository
     */
    private $markRepository;

    /**
     * @var TeachingSubjectRepository
     */
    private $teachingSubjectRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * MarkController constructor.
     * @param MarkRepository $markRepository
     * @param TeachingSubjectRepository $teachingSubjectRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        MarkRepository $markRepository,
        TeachingSubjectRepository $teachingSubjectRepository,
        UserRepository $userRepository
    )
    {
        $this->markRepository = $markRepository;
        $this->teachingSubjectRepository = $teachingSubjectRepository;
        $this->userRepository = $userRepository;
    }

    public function getStudentsWithAverageMarks()
    {
        $teachingSubjects = $this->teachingSubjectRepository->findAll();
        $students = $this->userRepository->findAll();
        $marks = $this->markRepository->findAll();
        $studentsWithAverageMarks = [];

        foreach ($students as $student) {

            if ($student->getUsername() == self::ADMIN_USERNAME) {
                continue;
            }

            $oneStudentNameWithAverageMarks = [];
            $oneStudentNameWithAverageMarks[] = $student->getName() . ' ' . $student->getSurname();
            foreach ($teachingSubjects as $teachingSubject) {
                $averageMark = $this->getAverageMark($marks, $student->getId(), $teachingSubject->getId());
                $oneStudentNameWithAverageMarks[] = $averageMark;
            }
            $studentsWithAverageMarks[] = $oneStudentNameWithAverageMarks;
        }
        return $studentsWithAverageMarks;
    }

    /**
     * @param array $marks
     * @param int $studentId
     * @param int $teachingSubjectId
     * @return float|string
     */
    private function getAverageMark($marks, $studentId, $teachingSubjectId)
    {
        $marksCounter = 0;
        $marksSum = 0;

        foreach ($marks as $mark) {
            if ($mark->getStudent()->getId() == $studentId && $mark->getTeachingSubject()->getId() == $teachingSubjectId) {
                $marksSum += $mark->getMark();
                $marksCounter++;
            }
        }

        if ($marksSum) {
            return $marksSum / $marksCounter;
        } else {
            return '-';
        }

    }
}
