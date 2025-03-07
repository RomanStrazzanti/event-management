<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Form\CalendarType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/calendar')]
class CalendarController extends AbstractController
{
    #[Route('/', name: 'calendar_list', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $calendars = $entityManager->getRepository(Calendar::class)->findAll();
        return $this->render('calendar/index.html.twig', ['calendars' => $calendars]);
    }

    #[Route('/create', name: 'calendar_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $calendar = new Calendar();
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($calendar);
            $entityManager->flush();
            return $this->redirectToRoute('calendar_list');
        }

        return $this->render('calendar/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/edit/{id}', name: 'calendar_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Calendar $calendar, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('calendar_list');
        }

        return $this->render('calendar/edit.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/delete/{id}', name: 'calendar_delete', methods: ['POST'])]
    public function delete(Request $request, Calendar $calendar, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$calendar->getId(), $request->request->get('_token'))) {
            $entityManager->remove($calendar);
            $entityManager->flush();
        }

        return $this->redirectToRoute('calendar_list');
    }
}
