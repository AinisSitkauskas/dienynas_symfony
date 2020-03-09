<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param UrlGeneratorInterface $urlGenerator
     * @param UserInterface|null $user
     * @return RedirectResponse|Response
     */
    public function index(UrlGeneratorInterface $urlGenerator, UserInterface $user = null)
    {
        if ($user instanceof User) {
            return $this->render('home/index.html.twig', [
                'user' => $user,
            ]);
        }
        return new RedirectResponse($urlGenerator->generate('app_login'));
    }
}
