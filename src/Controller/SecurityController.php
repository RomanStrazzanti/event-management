<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login', methods: ['GET'])]
    public function showLogin(): Response
    {
        return $this->render('login.html.twig');
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): RedirectResponse //void
    {
        return $this->redirectToRoute('app_login');
        // throw new \LogicException('This method is intercepted by the firewall logout.');
    }
}
