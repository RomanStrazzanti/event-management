<?php

namespace App\Controller;

use App\Repository\TarifRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user-dashboard')]
class UserDashboardController extends AbstractController
{
    #[Route('/', name: 'user_dashboard')]
    public function index(TarifRepository $tarifRepo): Response
    {
        $user = $this->getUser();
        $tarifs = $tarifRepo->findBy(['user' => $user]);

        return $this->render('user_dashboard/index.html.twig', [
            'tarifs' => $tarifs,
        ]);
    }
}
