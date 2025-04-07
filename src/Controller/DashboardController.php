<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(
        ClientRepository $clientRepo,
        EventRepository $eventRepo
    ): Response {
        $clientCount = $clientRepo->count([]);
        $eventCount = $eventRepo->count([]);
        $clients = $clientRepo->findAll();
    
        $events = $eventRepo->findAll();
        $totalRevenue = array_sum(array_map(fn($e) => $e->getTotalPrice(), $events));
    
        $currentYear = date('Y');
        $currentMonth = date('m');
    
        $yearRevenue = array_sum(array_map(fn($e) =>
            $e->getDate()->format('Y') == $currentYear ? $e->getTotalPrice() : 0, $events
        ));
    
        $monthRevenue = array_sum(array_map(fn($e) =>
            $e->getDate()->format('Y') == $currentYear && $e->getDate()->format('m') == $currentMonth ? $e->getTotalPrice() : 0, $events
        ));
    
        $recentEvents = $eventRepo->findBy([], ['date' => 'DESC'], 100);
    
        return $this->render('dashboard/index.html.twig', [
            'client_count' => $clientCount,
            'event_count' => $eventCount,
            'total_revenue' => $totalRevenue,
            'year_revenue' => $yearRevenue,
            'month_revenue' => $monthRevenue,
            'recent_events' => $recentEvents,
            'clients' => $clients, // 🔹 Ajout des clients pour le tableau
        ]);
    }
    
}
