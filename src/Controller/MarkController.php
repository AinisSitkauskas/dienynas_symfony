<?php

namespace App\Controller;

use App\Entity\Mark;
use App\Entity\TeachingSubject;
use App\Exceptions\PublicException;
use App\Form\MarkInsertFormType;
use App\Repository\MarkRepository;
use App\Repository\TeachingSubjectRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MarkController extends AbstractController
{
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
        UserRepository $userRepository)
    {
        $this->markRepository = $markRepository;
        $this->teachingSubjectRepository = $teachingSubjectRepository;
        $this->userRepository = $userRepository;
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

            if ($mark->getStudent()->getId() == 1) {
                throw new PublicException('Prašome pasirinkti mokinį');
            }

            $mark->setDate();
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
     * @param Request $request
     * @return Response
     * @throws PublicException
     */
    public function allMarks(Request $request): Response
    {
        $teachingSubjects = $this->teachingSubjectRepository->findAll();
        $students = $this->userRepository->findAll();
        $averageMarks = [];

        foreach ($teachingSubjects as $teachingSubject) {
            foreach ($students as $student) {
                $averageMark = $this->markRepository->getAverageMarks($student->getId(), $teachingSubject->getId());
                if ($averageMark[0]['average_mark']) {
                    $averageMarks[] = ['student' => $student->getName() . ' ' . $student->getSurname(),
                        'teachingSubject' => $teachingSubject->getTeachingSubject(),
                        'averageMark' => $averageMark[0]['average_mark']];
                }

            }
        }
        return $this->render('mark/allMarks.html.twig', [
            'averageMarks' => $averageMarks,
        ]);
    }
}
