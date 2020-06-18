<?php

namespace App\Controller;

use App\Entity\Mark;
use App\Exceptions\PublicException;
use App\Form\AverageMarkCalculatorFormType;
use App\Form\MarkInsertFormType;
use App\Repository\MarkRepository;
use App\Repository\TeachingSubjectRepository;
use App\Service\AverageMark;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class MarkController extends AbstractController
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
     * @var AverageMark
     */
    private $averageMark;

    /**
     * MarkController constructor.
     * @param MarkRepository $markRepository
     * @param TeachingSubjectRepository $teachingSubjectRepository
     * @param AverageMark $averageMark
     */
    public function __construct(
        MarkRepository $markRepository,
        TeachingSubjectRepository $teachingSubjectRepository,
        AverageMark $averageMark)
    {
        $this->markRepository = $markRepository;
        $this->teachingSubjectRepository = $teachingSubjectRepository;
        $this->averageMark = $averageMark;
    }

    /**
     * @Route("/mark/insert", name="insert_mark")
     * @param Request $request
     * @return Response
     * @throws PublicException
     */
    public function insertMark(Request $request): Response
    {
        $mark = new Mark();
        $form = $this->createForm(MarkInsertFormType::class, $mark);
        $form->handleRequest($request);
        $markInserted = false;
        if ($form->isSubmitted()) {

            if (!$form->isValid()) {
                $message = $form->getErrors(true);
                throw new PublicException($message);
            }

            if ($mark->getStudent()->getUsername() == self::ADMIN_USERNAME) {
                throw new PublicException('Prašome pasirinkti mokinį');
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mark);
            $entityManager->flush();
            $markInserted = true;
        }

        return $this->render('mark/insertMark.html.twig', [
            'markInserted' => $markInserted,
            'markInsertForm' => $form->createView(),
        ]);

    }


    /**
     * @Route("/mark/all", name="all_marks")
     * @return Response
     */
    public function allMarks(): Response
    {
        $teachingSubjects = $this->teachingSubjectRepository->findAll();
        $studentsWithAverageMarks = $this->averageMark->getStudentsWithAverageMarks();

        return $this->render('mark/allMarks.html.twig', [
            'studentsWithAverageMarks' => $studentsWithAverageMarks,
            'teachingSubjects' => $teachingSubjects
        ]);
    }

    /**
     * @Route("/mark/my", name="my_marks")
     * @param Request $request
     * @param UserInterface $user
     * @return Response
     * @throws PublicException
     */
    public function myMarks(Request $request, UserInterface $user): Response
    {
        $userTeachingSubjectsWithAverageMarks = $this->averageMark->getUserTeachingSubjectsWithAverageMarks($user->getId());

        $form = $this->createForm(AverageMarkCalculatorFormType::class);
        $form->handleRequest($request);
        $calculationResult = '';

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $message = $form->getErrors(true);
                throw new PublicException($message);
            }

            $formData = $form->getData();
            $futureAverageMark = $this->averageMark->getUserFutureAverageMark($user->getId(), $formData['teachingSubject']->getId(), $formData['marks']);
            $teachingSubject = strtolower($formData['teachingSubject']->getTeachingSubject());
            $teachingSubject = substr($teachingSubject, 0, -1) . 'os';
            $calculationResult = "Gavus papildomus pažymius: " . $formData['marks'] . " jūsų vidurkis iš " . $teachingSubject . " yra " . $futureAverageMark;
        }

        return $this->render('mark/myMarks.html.twig', [
            'userTeachingSubjectsWithAverageMarks' => $userTeachingSubjectsWithAverageMarks,
            'averageMarkCalculatorForm' => $form->createView(),
            'formSubmited' => $form->isSubmitted(),
            'calculationResult' => $calculationResult,
        ]);
    }

    /**
     * @Route("/mark/top", name="top_marks")
     * @return Response
     */
    public function topMarks(): Response
    {
        $topStudents = $this->markRepository->getTopStudents();
        $topStudentsList = [];

        if ($topStudents) {
            foreach ($topStudents as $topStudent) {
                $topStudentsList[] = ['student' => $topStudent['name'] . ' ' . $topStudent['surname'],
                    'averageMark' => $topStudent['averageMark']];
            }
        }
        return $this->render('mark/topMarks.html.twig', [
            'topStudentsList' => $topStudentsList,
        ]);
    }
}
