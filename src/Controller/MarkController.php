<?php

namespace App\Controller;

use App\Entity\Mark;
use App\Exceptions\PublicException;
use App\Form\MarkInsertFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MarkController extends AbstractController
{
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
}
