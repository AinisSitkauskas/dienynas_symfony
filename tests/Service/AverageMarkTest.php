<?php

namespace App\Tests\Service;

use App\Entity\Mark;
use App\Entity\TeachingSubject;
use App\Entity\User;
use App\Repository\MarkRepository;
use App\Repository\TeachingSubjectRepository;
use App\Repository\UserRepository;
use App\Service\AverageMark;
use PHPUnit\Framework\TestCase;

class AverageMarkTest extends TestCase
{
    /**
     * @var AverageMark
     */
    private $averageMark;

    /**
     * @var UserRepository
     */
    private $userRepositoryMock;

    /**
     * @var MarkRepository
     */
    private $markRepositoryMock;

    /**
     * @var TeachingSubjectRepository
     */
    private $teachingSubjectRepositoryMock;

    /**
     * @var array
     */
    private $students = [];

    /**
     * @var array
     */
    private $teachingSubjects = [];

    /**
     * @var array
     */
    private $marks = [];

    public function setUp(): void
    {
        $this->setUpData();
        $this->userRepositoryMock = $this->getUserRepositoryMock();
        $this->teachingSubjectRepositoryMock = $this->getTeachingSubjectRepositoryMock();
        $this->markRepositoryMock = $this->getMarkRepositoryMock();
        $this->averageMark = new AverageMark($this->markRepositoryMock, $this->teachingSubjectRepositoryMock, $this->userRepositoryMock);
    }

    public function testGetStudentsWithAverageMarks()
    {
        $this->userRepositoryMock->method('findAll')->willReturn($this->students);
        $this->markRepositoryMock->method('findAll')->willReturn($this->marks);
        $this->teachingSubjectRepositoryMock->method('findAll')->willReturn($this->teachingSubjects);

        $result = [];
        $result[] = ['Jonas Jonaitis', 8, '-'];
        $result[] = ['Petras Petraitis', '-', 3];

        $this->assertSame($result, $this->averageMark->getStudentsWithAverageMarks());
    }

    public function setUpData()
    {
        $studentOne = $this->createMock(User::class);
        $studentOne->method('getId')->willReturn(1);
        $studentOne->method('getName')->willReturn('Jonas');
        $studentOne->method('getSurname')->willReturn('Jonaitis');
        $this->students[] = $studentOne;

        $studentTwo = $this->createMock(User::class);
        $studentTwo->method('getId')->willReturn(2);
        $studentTwo->method('getName')->willReturn('Petras');
        $studentTwo->method('getSurname')->willReturn('Petraitis');
        $this->students[] = $studentTwo;

        $teachingSubjectOne =  $this->createMock(TeachingSubject::class);
        $teachingSubjectOne->method('getId')->willReturn(1);
        $teachingSubjectOne->method('getTeachingSubject')->willReturn('Matematika');
        $this->teachingSubjects[] = $teachingSubjectOne;

        $teachingSubjectTwo = $this->createMock(TeachingSubject::class);
        $teachingSubjectTwo->method('getId')->willReturn(2);
        $teachingSubjectTwo->method('getTeachingSubject')->willReturn('Istorija');
        $this->teachingSubjects[] = $teachingSubjectTwo;

        $markOne = new Mark();
        $markOne->setMark(9);
        $markOne->setStudent($studentOne);
        $markOne->setTeachingSubject($teachingSubjectOne);
        $this->marks[] = $markOne;

        $markTwo = new Mark();
        $markTwo->setMark(7);
        $markTwo->setStudent($studentOne);
        $markTwo->setTeachingSubject($teachingSubjectOne);
        $this->marks[] = $markTwo;

        $markThree = new Mark();
        $markThree->setMark(3);
        $markThree->setStudent($studentTwo);
        $markThree->setTeachingSubject($teachingSubjectTwo);
        $this->marks[] = $markThree;
    }

    public function getUserRepositoryMock()
    {
        return $this->createMock(UserRepository::class);
    }

    public function getTeachingSubjectRepositoryMock()
    {
        return $this->createMock(TeachingSubjectRepository::class);
    }

    public function getMarkRepositoryMock()
    {
        return $this->createMock(MarkRepository::class);
    }

}
