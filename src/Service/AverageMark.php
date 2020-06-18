<?php

namespace App\Service;

use App\Entity\Mark;
use App\Exceptions\PublicException;
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

    /**
     * @param int $studentId
     * @return array
     */
    public function getUserTeachingSubjectsWithAverageMarks($studentId)
    {
        $teachingSubjects = $this->teachingSubjectRepository->findAll();
        $marks = $this->markRepository->findAll();
        $userTeachingSubjectsWithAverageMarks = [];

        foreach ($teachingSubjects as $teachingSubject) {
            $averageMark = $this->getAverageMark($marks, $studentId, $teachingSubject->getId());
            $userTeachingSubjectsWithAverageMarks[] = [$teachingSubject->getTeachingSubject(), $averageMark];
        }

        return $userTeachingSubjectsWithAverageMarks;
    }

    /**
     * @param int $studentId
     * @param int $teachingSubjectId
     * @param string $futureMarks
     * @return float
     * @throws PublicException
     */
    public function getUserFutureAverageMark($studentId, $teachingSubjectId, $futureMarks)
    {
        $currentMarks = $this->markRepository->getMarks($studentId, $teachingSubjectId);
        $markCounter = 0;
        $markSum = 0;

        foreach ($currentMarks as $currentMark) {
            $markSum += $currentMark['mark'];
            $markCounter++;
        }

        if (strpos($futureMarks, ',')) {
            $futureMarks = explode(',', $futureMarks);
        } elseif (!is_numeric($futureMarks)) {
            throw new PublicException("Neteisingai įvesti pažymiai");
        }

        if (!is_array($futureMarks)) {
            if ($futureMarks < 1 || $futureMarks > 10) {
                throw new PublicException("Prašome vesti pažymius tarp 1 ir 10");
            }
            $markSum += $futureMarks;
            $markCounter++;
            return $markSum / $markCounter;
        }

        foreach ($futureMarks as $futureMark) {
            $futureMark = trim($futureMark);
            if (is_numeric($futureMark)) {
                if ($futureMark < 1 || $futureMark > 10) {
                    throw new PublicException("Prašome vesti pažymius tarp 1 ir 10");
                }
                $markSum += $futureMark;
                $markCounter++;
            }
        }
        return $markSum / $markCounter;
    }
}
