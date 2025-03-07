<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\EventRepository;
use App\Repository\InvoiceRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(
        ClientRepository $clientRepo,
        EventRepository $eventRepo,
        InvoiceRepository $invoiceRepo,
        TaskRepository $taskRepo
    ): Response {
        return $this->render('dashboard/index.html.twig', [
            'client_count' => $clientRepo->count([]),
            'event_count' => $eventRepo->count([]),
            'invoice_count' => $invoiceRepo->count([]),
            'task_count' => $taskRepo->count([]),
            'recent_clients' => $clientRepo->findBy([], ['id' => 'DESC'], 5),
            'recent_events' => $eventRepo->findBy([], ['id' => 'DESC'], 5),
            'recent_invoices' => $invoiceRepo->findBy([], ['id' => 'DESC'], 5),
            'recent_tasks' => $taskRepo->findBy([], ['id' => 'DESC'], 5),
        ]);
    }
}
