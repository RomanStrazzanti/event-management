<?php

namespace App\Controller;

use App\Entity\EventPartner;
use App\Form\EventPartnerType;
use App\Repository\EventPartnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/event/partner')]
final class EventPartnerController extends AbstractController
{
    #[Route(name: 'app_event_partner_index', methods: ['GET'])]
    public function index(EventPartnerRepository $eventPartnerRepository): Response
    {
        return $this->render('event_partner/index.html.twig', [
            'event_partners' => $eventPartnerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_event_partner_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $eventPartner = new EventPartner();
        $form = $this->createForm(EventPartnerType::class, $eventPartner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($eventPartner);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_partner_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event_partner/new.html.twig', [
            'event_partner' => $eventPartner,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_partner_show', methods: ['GET'])]
    public function show(EventPartner $eventPartner): Response
    {
        return $this->render('event_partner/show.html.twig', [
            'event_partner' => $eventPartner,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_partner_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EventPartner $eventPartner, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventPartnerType::class, $eventPartner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_event_partner_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event_partner/edit.html.twig', [
            'event_partner' => $eventPartner,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_partner_delete', methods: ['POST'])]
    public function delete(Request $request, EventPartner $eventPartner, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$eventPartner->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($eventPartner);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_partner_index', [], Response::HTTP_SEE_OTHER);
    }
}
