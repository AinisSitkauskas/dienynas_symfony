<?php

namespace App\Controller;

use App\Entity\User;
use App\Exceptions\PublicException;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{

    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @throws PublicException
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if (!$form->isValid()) {
                $message = $form->getErrors(true);
                throw new PublicException($message);
            }

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->render('registration/successfulRegistration.html.twig');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete", name="delete_user")
     * methods={"POST"}
     * @param UserInterface $user
     * @param SessionInterface $session
     * @param TokenStorageInterface $tokenStorage
     * @return Response
     * @throws PublicException
     */
    public function delete(UserInterface $user, SessionInterface $session, TokenStorageInterface $tokenStorage)
    {
        if ($user->getUsername() === 'admin') {
            throw new PublicException("Administratoriaus ištrinti negalima");
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        $tokenStorage->setToken(null);
        $session->invalidate();

        return $this->render('delete/userDeleted.html.twig');
    }

}
